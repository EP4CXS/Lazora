<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import { dashboard } from '@/routes/admin';

type Product = {
    id: number;
    name: string;
    slug: string;
    category: string;
    description: string | null;
    price: string;
    stock: number;
    is_featured: boolean;
    is_active: boolean;
    image_url: string | null;
    rating: string;
    created_at: string;
    updated_at: string;
};

defineProps<{
    product: Product;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard() },
            { title: 'Products', href: ProductController.index.url() },
            { title: 'Details', href: ProductController.index.url() },
        ],
    },
});

</script>

<template>
    <Head :title="product.name" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ product.name }}</h1>
                <p class="mt-1 font-mono text-xs text-muted-foreground">{{ product.slug }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <Badge variant="secondary">{{ product.category }}</Badge>
                    <Badge v-if="product.is_featured">Featured</Badge>
                    <Badge :variant="product.is_active ? 'default' : 'outline'">
                        {{ product.is_active ? 'Active' : 'Hidden' }}
                    </Badge>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <Button variant="outline" class="rounded-xl" as-child>
                    <Link :href="ProductController.edit.url(product.id)">
                        <Pencil class="mr-2 size-4" />
                        Edit
                    </Link>
                </Button>
                <Form v-bind="ProductController.destroy.form(product.id)" #default="{ processing }">
                    <Button type="submit" variant="destructive" class="rounded-xl" :disabled="processing">
                        <Trash2 class="mr-2 size-4" />
                        Delete
                    </Button>
                </Form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div
                class="overflow-hidden rounded-2xl border border-border/80 bg-card/40 shadow-sm backdrop-blur-sm"
            >
                <div class="aspect-[4/3] bg-muted/30">
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.name"
                        class="size-full object-cover"
                    />
                    <div v-else class="flex size-full items-center justify-center text-sm text-muted-foreground">
                        No image
                    </div>
                </div>
            </div>

            <div class="space-y-4 rounded-2xl border border-border/80 bg-card/40 p-6 shadow-sm backdrop-blur-sm">
                <div>
                    <p class="text-xs font-semibold uppercase text-muted-foreground">Price</p>
                    <p class="mt-1 text-2xl font-semibold">{{ formatPhp(product.price) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-muted-foreground">Stock</p>
                    <p class="mt-1 text-lg">{{ product.stock }} units</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-muted-foreground">Rating</p>
                    <p class="mt-1 text-lg tabular-nums">{{ Number(product.rating).toFixed(1) }} / 5</p>
                </div>
                <div v-if="product.description">
                    <p class="text-xs font-semibold uppercase text-muted-foreground">Description</p>
                    <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-muted-foreground">
                        {{ product.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
