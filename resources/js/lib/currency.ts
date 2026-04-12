/**
 * Format amounts as Philippine Peso (₱) for storefront and admin displays.
 */
export function formatPhp(value: string | number): string {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number(value));
}
