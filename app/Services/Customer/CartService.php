<?php

namespace App\Services\Customer;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * @return array{items: Collection<int, CartItem>, subtotal: float, count: int}
     */
    public function summary(User $user): array
    {
        $items = $user->cartItems()->with('product')->get();
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float) $item->product->price * $item->quantity;
        }

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'count' => (int) $items->sum('quantity'),
        ];
    }

    public function add(User $user, int $productId, int $quantity, string $size = ''): CartItem
    {
        $product = Product::query()->where('is_active', true)->findOrFail($productId);

        $normalizedSize = trim($size);

        $item = CartItem::query()->firstOrNew([
            'user_id' => $user->id,
            'product_id' => $productId,
            'size' => $normalizedSize,
        ]);

        $newQty = $item->exists ? $item->quantity + $quantity : $quantity;

        if ($newQty > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => __('Not enough stock available.'),
            ]);
        }

        $item->quantity = $newQty;
        $item->size = $normalizedSize;
        $item->save();

        return $item->load('product');
    }

    public function updateQuantity(User $user, CartItem $cartItem, int $quantity): void
    {
        if ($cartItem->user_id !== $user->id) {
            abort(403);
        }

        if ($quantity < 1) {
            $cartItem->delete();

            return;
        }

        $product = $cartItem->product;
        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => __('Not enough stock available.'),
            ]);
        }

        $cartItem->update(['quantity' => $quantity]);
    }

    public function remove(User $user, CartItem $cartItem): void
    {
        if ($cartItem->user_id !== $user->id) {
            abort(403);
        }

        $cartItem->delete();
    }
}
