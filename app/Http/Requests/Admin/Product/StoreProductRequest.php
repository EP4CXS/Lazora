<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products', 'slug')],
            'category' => ['required', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:128'],
            'sizes' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'is_featured' => ['required', 'in:0,1'],
            'is_active' => ['required', 'in:0,1'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'is_featured' => (bool) (int) $this->input('is_featured'),
            'is_active' => (bool) (int) $this->input('is_active'),
        ]);
    }
}
