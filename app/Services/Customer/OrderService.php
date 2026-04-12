<?php

namespace App\Services\Customer;

use App\Enums\OrderFulfillmentStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * @return LengthAwarePaginator<Order>
     */
    public function paginateForCustomer(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->with(['items.product'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findOwned(User $user, Order $order): Order
    {
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        return $order->load(['items.product']);
    }

    public function checkoutFromCart(User $user): Order
    {
        return DB::transaction(function () use ($user) {
            $cartRows = $user->cartItems()->lockForUpdate()->with('product')->get();
            if ($cartRows->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => __('Your cart is empty.'),
                ]);
            }

            $total = 0.0;
            foreach ($cartRows as $row) {
                $product = $row->product;
                if ($product->stock < $row->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => __(':name is not available in the requested quantity.', ['name' => $product->name]),
                    ]);
                }
                $total += (float) $product->price * $row->quantity;
            }

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => $this->uniqueOrderNumber(),
                'status' => OrderFulfillmentStatus::Placed,
                'payment_status' => PaymentStatus::Paid,
                'total' => round($total, 2),
                'notes' => null,
            ]);

            foreach ($cartRows as $row) {
                $product = $row->product;
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $row->quantity,
                    'unit_price' => $product->price,
                    'line_total' => round((float) $product->price * $row->quantity, 2),
                ]);
                $product->decrement('stock', $row->quantity);
            }

            $user->cartItems()->delete();

            return $order->load(['items.product']);
        });
    }

    public function placeQuickOrder(User $user, int $productId, int $quantity): Order
    {
        return DB::transaction(function () use ($user, $productId, $quantity) {
            $product = Product::query()
                ->where('is_active', true)
                ->lockForUpdate()
                ->findOrFail($productId);

            if ($product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => __('Not enough stock available.'),
                ]);
            }

            $total = round((float) $product->price * $quantity, 2);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => $this->uniqueOrderNumber(),
                'status' => OrderFulfillmentStatus::Placed,
                'payment_status' => PaymentStatus::Paid,
                'total' => $total,
                'notes' => null,
            ]);

            OrderItem::query()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'line_total' => $total,
            ]);

            $product->decrement('stock', $quantity);

            return $order->load(['items.product']);
        });
    }

    private function uniqueOrderNumber(): string
    {
        for ($i = 0; $i < 15; $i++) {
            $candidate = 'ORD-'.Str::upper(Str::random(10));
            if (! Order::query()->where('order_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'ORD-'.Str::upper(Str::replace('-', '', (string) Str::uuid()));
    }
}
