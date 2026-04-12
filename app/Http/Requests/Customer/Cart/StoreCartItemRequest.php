<?php

namespace App\Http\Requests\Customer\Cart;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCartItemRequest extends FormRequest
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
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'size' => ['nullable', 'string', 'max:32'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $productId = $this->integer('product_id');
            $product = Product::query()->find($productId);

            if ($product === null) {
                return;
            }

            $allowed = $product->available_sizes;

            if ($allowed === []) {
                return;
            }

            $size = trim((string) ($this->input('size') ?? ''));

            if ($size === '' || ! in_array($size, $allowed, true)) {
                $validator->errors()->add('size', __('Please select a valid size.'));
            }
        });
    }
}
