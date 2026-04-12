<?php

namespace App\Http\Requests\Customer\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
            'from_cart' => ['sometimes', 'boolean'],
            'product_id' => [
                Rule::requiredIf(fn () => ! $this->boolean('from_cart')),
                'nullable',
                'integer',
                'exists:products,id',
            ],
            'quantity' => [
                Rule::requiredIf(fn () => ! $this->boolean('from_cart')),
                'nullable',
                'integer',
                'min:1',
                'max:999',
            ],
        ];
    }
}
