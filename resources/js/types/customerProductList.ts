/** Product row shape returned on the customer catalog (products index). */
export type CustomerProductListItem = {
    id: number;
    name: string;
    slug: string;
    category: string;
    color: string | null;
    sizes: string | null;
    /** Parsed from admin `sizes` (comma-separated, etc.). */
    available_sizes: string[];
    description: string | null;
    price: string;
    stock: number;
    is_featured: boolean;
    image_url: string | null;
    rating: string;
    created_at: string;
};
