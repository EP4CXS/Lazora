/**
 * Compact header label for the customer app shell, derived from the current URL.
 */
export function resolveCustomerPageTitle(fullPath: string): string {
    const base = typeof window !== 'undefined' ? window.location.origin : 'http://localhost';
    let url: URL;

    try {
        url = new URL(fullPath, base);
    } catch {
        return 'Customer';
    }

    const path = url.pathname.replace(/\/$/, '') || '/';
    const tab = url.searchParams.get('tab') ?? 'orders';

    if (path === '/customer/dashboard') {
        return 'Customer Dashboard';
    }

    if (path === '/customer/products') {
        return 'Products';
    }

    if (path.startsWith('/customer/products/')) {
        return 'Product';
    }

    if (path === '/customer/cart') {
        return 'Cart';
    }

    if (path === '/customer/tracking') {
        return 'Tracking';
    }

    if (path === '/customer/orders') {
        if (tab === 'cart') {
            return 'Cart';
        }

        if (tab === 'tracking') {
            return 'Tracking';
        }

        return 'My Orders';
    }

    if (path.startsWith('/customer/orders/')) {
        return 'Order';
    }

    return 'Customer';
}
