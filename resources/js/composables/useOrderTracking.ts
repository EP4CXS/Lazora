export const ORDER_STEPS = [
    { key: 'placed', label: 'Order placed' },
    { key: 'confirmed', label: 'Confirmed' },
    { key: 'packed', label: 'Packed' },
    { key: 'shipped', label: 'Shipped' },
    { key: 'delivered', label: 'Delivered' },
] as const;

export type OrderStatusKey = (typeof ORDER_STEPS)[number]['key'] | 'cancelled';

export function getOrderTracking(status: string) {
    if (status === 'cancelled') {
        return {
            isCancelled: true,
            currentIndex: -1,
            steps: ORDER_STEPS,
        };
    }

    const idx = ORDER_STEPS.findIndex((s) => s.key === status);

    return {
        isCancelled: false,
        currentIndex: idx >= 0 ? idx : 0,
        steps: ORDER_STEPS,
    };
}
