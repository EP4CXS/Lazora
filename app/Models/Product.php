<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $appends = [
        'available_sizes',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'color',
        'sizes',
        'description',
        'price',
        'stock',
        'is_featured',
        'is_active',
        'image_url',
        'rating',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'decimal:2',
        ];
    }

    /**
     * EU / generic numeric sizes used by the storefront (parsed from admin `sizes` text).
     *
     * @return Attribute<never, never>
     */
    protected function availableSizes(): Attribute
    {
        return Attribute::make(
            get: fn (): array => self::normalizeSizesString($this->sizes),
        );
    }

    /**
     * @return array<int, string>
     */
    public static function normalizeSizesString(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return ['37', '38', '39', '40', '41', '42', '43'];
        }

        $parts = preg_split('/[,;|]/', $raw) ?: [];

        $parsed = collect($parts)
            ->map(fn (string $s) => trim($s))
            ->filter(fn (string $s) => $s !== '')
            ->values()
            ->all();

        return $parsed === [] ? ['37', '38', '39', '40', '41', '42', '43'] : $parsed;
    }

}
