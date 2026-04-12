<?php

namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * @return LengthAwarePaginator<Product>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        $slug = ! empty($data['slug']) ? $data['slug'] : Str::slug($data['name']);
        $imageUrl = $this->resolveImageUrl($data);

        return Product::create([
            'name' => $data['name'],
            'slug' => $slug,
            'category' => $data['category'],
            'color' => $data['color'] ?? null,
            'sizes' => $data['sizes'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_featured' => $data['is_featured'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'image_url' => $imageUrl,
            'rating' => $data['rating'] ?? 4.5,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product
    {
        $name = $data['name'] ?? $product->name;
        $slug = array_key_exists('slug', $data)
            ? (! empty($data['slug']) ? $data['slug'] : Str::slug($name))
            : $product->slug;

        $imageUrl = $this->resolveImageUrl($data, $product->image_url);

        $product->update([
            'name' => $name,
            'slug' => $slug,
            'category' => $data['category'] ?? $product->category,
            'color' => array_key_exists('color', $data) ? $data['color'] : $product->color,
            'sizes' => array_key_exists('sizes', $data) ? $data['sizes'] : $product->sizes,
            'description' => array_key_exists('description', $data) ? $data['description'] : $product->description,
            'price' => $data['price'] ?? $product->price,
            'stock' => $data['stock'] ?? $product->stock,
            'is_featured' => $data['is_featured'] ?? $product->is_featured,
            'is_active' => $data['is_active'] ?? $product->is_active,
            'image_url' => $imageUrl,
            'rating' => $data['rating'] ?? $product->rating,
        ]);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveImageUrl(array $data, ?string $current = null): ?string
    {
        $file = $data['image'] ?? null;
        if ($file instanceof UploadedFile) {
            $path = $file->store('products', 'public');

            return Storage::url($path);
        }

        if (array_key_exists('image_url', $data)) {
            return $data['image_url'] !== '' && $data['image_url'] !== null
                ? (string) $data['image_url']
                : null;
        }

        return $current;
    }
}
