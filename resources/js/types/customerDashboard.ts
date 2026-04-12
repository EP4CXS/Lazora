export type DashboardProductSummary = {
    id: number;
    name: string;
    slug: string;
    category: string;
    color: string | null;
    price: string;
    stock: number;
    is_featured: boolean;
    image_url: string | null;
    rating: string;
    /** When set, show as strikethrough “was” price (e.g. MSRP). */
    compare_at_price?: string | null;
};
