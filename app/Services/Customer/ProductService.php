<?php

namespace App\Services\Customer;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<Product>
     */
    public function paginate(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->query($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Product>
     */
    public function query(array $filters): Builder
    {
        $query = Product::query()
            ->where('is_active', true)
            ->select([
                'id',
                'name',
                'slug',
                'category',
                'color',
                'sizes',
                'description',
                'price',
                'stock',
                'is_featured',
                'image_url',
                'rating',
                'created_at',
            ]);

        if (! empty($filters['search'])) {
            $search = (string) $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', (string) $filters['category']);
        }

        if (! empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        if ($filters['min_price'] ?? null) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }

        if ($filters['max_price'] ?? null) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        $sort = (string) ($filters['sort'] ?? 'newest');

        return match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'featured' => $query->orderByDesc('is_featured')->latest(),
            default => $query->latest(),
        };
    }

    /**
     * @return array<int, string>
     */
    public function categories(): array
    {
        return Product::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values()
            ->all();
    }

    /**
     * Catalog slices for the customer dashboard (spotlight, lists, recent grid).
     *
     * @return array{
     *     spotlight: array<string, mixed>|null,
     *     topPicks: array<int, array<string, mixed>>,
     *     trending: array<int, array<string, mixed>>,
     *     highestRated: array<int, array<string, mixed>>,
     *     recentProducts: array<int, array<string, mixed>>,
     * }
     */
    public function dashboardCatalog(): array
    {
        $inStock = fn (Builder $q): Builder => $q->where('is_active', true)->where('stock', '>', 0);

        $spotlight = Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->first()
            ?? Product::query()
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->latest()
                ->first();

        $topPicks = Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('is_featured', true)
            ->orderByDesc('rating')
            ->limit(5)
            ->get();

        if ($topPicks->isEmpty()) {
            $topPicks = Product::query()
                ->tap($inStock)
                ->orderByDesc('rating')
                ->limit(5)
                ->get();
        }

        $trending = Product::query()
            ->tap($inStock)
            ->latest()
            ->limit(5)
            ->get();

        $highestRated = Product::query()
            ->tap($inStock)
            ->orderByDesc('rating')
            ->orderByDesc('stock')
            ->limit(5)
            ->get();

        $recentProducts = Product::query()
            ->tap($inStock)
            ->latest()
            ->limit(8)
            ->get();

        return [
            'spotlight' => $spotlight ? $this->productSummary($spotlight) : null,
            'topPicks' => $this->mapProductSummaries($topPicks),
            'trending' => $this->mapProductSummaries($trending),
            'highestRated' => $this->mapProductSummaries($highestRated),
            'recentProducts' => $this->mapProductSummaries($recentProducts),
        ];
    }

    /**
     * @param  Collection<int, Product>  $products
     * @return array<int, array<string, mixed>>
     */
    private function mapProductSummaries(Collection $products): array
    {
        return $products->map(fn (Product $product): array => $this->productSummary($product))->values()->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function productSummary(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category,
            'color' => $product->color,
            'price' => (string) $product->price,
            'stock' => $product->stock,
            'is_featured' => $product->is_featured,
            'image_url' => $product->image_url,
            'rating' => (string) $product->rating,
            'compare_at_price' => null,
        ];
    }
}
