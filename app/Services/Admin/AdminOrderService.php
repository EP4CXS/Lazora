<?php

namespace App\Services\Admin;

use App\Enums\OrderFulfillmentStatus;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminOrderService
{
    /**
     * @return LengthAwarePaginator<Order>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Order::query()
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function pendingCount(): int
    {
        return Order::query()
            ->where('status', OrderFulfillmentStatus::Placed)
            ->count();
    }

    public function totalCount(): int
    {
        return Order::query()->count();
    }

    public function confirm(Order $order): Order
    {
        if ($order->status !== OrderFulfillmentStatus::Placed) {
            throw ValidationException::withMessages([
                'order' => __('Only orders awaiting confirmation can be confirmed.'),
            ]);
        }

        $order->update([
            'status' => OrderFulfillmentStatus::Confirmed,
            'denial_reason' => null,
        ]);

        return $order->fresh()->load(['user', 'items.product']);
    }

    public function deny(Order $order, string $reason): Order
    {
        if ($order->status !== OrderFulfillmentStatus::Placed) {
            throw ValidationException::withMessages([
                'order' => __('Only orders awaiting confirmation can be denied.'),
            ]);
        }

        return DB::transaction(function () use ($order, $reason) {
            $order->load('items.product');

            foreach ($order->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }

            $order->update([
                'status' => OrderFulfillmentStatus::Cancelled,
                'denial_reason' => $reason,
            ]);

            return $order->fresh()->load(['user', 'items.product']);
        });
    }
}
