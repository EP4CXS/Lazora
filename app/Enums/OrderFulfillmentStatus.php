<?php

namespace App\Enums;

enum OrderFulfillmentStatus: string
{
    case Placed = 'placed';
    case Confirmed = 'confirmed';
    case Packed = 'packed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    /**
     * @return list<self>
     */
    public static function trackingSteps(): array
    {
        return [
            self::Placed,
            self::Confirmed,
            self::Packed,
            self::Shipped,
            self::Delivered,
        ];
    }

    public function stepIndex(): int
    {
        return match ($this) {
            self::Placed => 0,
            self::Confirmed => 1,
            self::Packed => 2,
            self::Shipped => 3,
            self::Delivered => 4,
            self::Cancelled => -1,
        };
    }
}
