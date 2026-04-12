<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Product $product */
        $product = $this->route('product');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:128'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'rating' => ['sometimes', 'required', 'numeric', 'min:0', 'max:5'],
            'is_featured' => ['sometimes', 'in:0,1'],
            'is_active' => ['sometimes', 'in:0,1'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->has('is_featured')) {
            $this->merge(['is_featured' => (bool) (int) $this->input('is_featured')]);
        }
        if ($this->has('is_active')) {
            $this->merge(['is_active' => (bool) (int) $this->input('is_active')]);
        }
    }
}
