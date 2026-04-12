<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ShoppingBag, Zap } from 'lucide-vue-next';
import StarRating from '@/components/customer/StarRating.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import CustomerLayout from '@/layouts/app/CustomerLayout.vue';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';

type Product = {
    id: number;
    name: string;
    slug: string;
    category: string;
    color: string | null;
    sizes: string | null;
    available_sizes: string[];
    description: string | null;
    price: string;
    stock: number;
    is_featured: boolean;
    image_url: string | null;
    rating: string;
};

defineProps<{
    product: Product;
}>();

defineOptions({
    layout: CustomerLayout,
});
</script>

<template>
    <Head :title="product.name" />

    <div class="mx-auto flex max-w-5xl flex-col gap-8 p-4 pb-12">
        <Button variant="ghost" size="sm" class="-ml-2 w-fit rounded-xl text-muted-foreground" as-child>
            <Link :href="customer.products.url()">
                <ArrowLeft class="mr-2 size-4" />
                Back to products
            </Link>
        </Button>

        <div class="grid gap-8 lg:grid-cols-2">
            <div
                class="group/prod overflow-hidden rounded-2xl border border-border/70 bg-card/40 shadow-sm transition hover:border-primary/20 hover:shadow-lg hover:shadow-[0_24px_60px_-16px_rgba(99,102,241,0.15)]"
            >
                <div class="relative aspect-square overflow-hidden rounded-t-2xl">
                    <div
                        class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_78%_74%_at_50%_55%,rgba(99,102,241,0.12),transparent_70%)] opacity-90"
                    />
                    <div
                        class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover/prod:opacity-100 bg-[radial-gradient(ellipse_82%_78%_at_50%_50%,rgba(99,102,241,0.18),transparent_74%)]"
                    />
                    <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.name"
                        class="relative z-[1] size-full object-contain object-center px-6 py-8 transition duration-500 ease-out drop-shadow-[0_16px_44px_rgba(0,0,0,0.42)] group-hover/prod:scale-[1.03] group-hover/prod:drop-shadow-[0_24px_56px_rgba(0,0,0,0.55)] dark:drop-shadow-[0_18px_48px_rgba(0,0,0,0.6)] sm:px-8 sm:py-10"
                    />
                    <div
                        v-else
                        class="relative z-[1] flex size-full items-center justify-center bg-muted/10 text-sm text-muted-foreground"
                    >
                        No image
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-5">
                <div class="flex flex-wrap items-center gap-2">
                    <Badge variant="outline" class="rounded-lg uppercase">{{ product.category }}</Badge>
                    <Badge v-if="product.is_featured">Featured</Badge>
                </div>
                <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ product.name }}</h1>
                <div class="flex flex-wrap items-center gap-3">
                    <StarRating :rating="Number(product.rating)" size="md" />
                    <span class="text-sm text-muted-foreground tabular-nums">{{ Number(product.rating).toFixed(1) }} / 5</span>
                </div>
                <p class="text-3xl font-semibold tabular-nums">{{ formatPhp(product.price) }}</p>
                <p
                    class="text-sm font-medium"
                    :class="product.stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive'"
                >
                    {{ product.stock > 0 ? `${product.stock} available` : 'Out of stock' }}
                </p>
                <dl v-if="product.color || product.sizes" class="grid gap-2 text-sm">
                    <div v-if="product.color" class="flex flex-wrap gap-x-2 gap-y-1">
                        <dt class="font-medium text-foreground">Color</dt>
                        <dd class="text-muted-foreground">{{ product.color }}</dd>
                    </div>
                    <div v-if="product.sizes" class="flex flex-wrap gap-x-2 gap-y-1">
                        <dt class="font-medium text-foreground">Sizes</dt>
                        <dd class="text-muted-foreground">{{ product.sizes }}</dd>
                    </div>
                </dl>
                <p v-if="product.description" class="whitespace-pre-wrap text-sm leading-relaxed text-muted-foreground">
                    {{ product.description }}
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <Form v-bind="customer.cart.store.form()" #default="{ processing }" class="flex-1">
                        <input type="hidden" name="product_id" :value="product.id" />
                        <input type="hidden" name="quantity" value="1" />
                        <input type="hidden" name="size" :value="product.available_sizes[0] ?? '37'" />
                        <Button
                            type="submit"
                            variant="outline"
                            class="w-full rounded-xl"
                            :disabled="processing || product.stock < 1"
                        >
                            <ShoppingBag class="mr-2 size-4" />
                            Add to cart
                        </Button>
                    </Form>
                    <Form v-bind="customer.orders.store.form()" #default="{ processing }" class="flex-1">
                        <input type="hidden" name="product_id" :value="product.id" />
                        <input type="hidden" name="quantity" value="1" />
                        <Button type="submit" class="w-full rounded-xl" :disabled="processing || product.stock < 1">
                            <Zap class="mr-2 size-4" />
                            Order now
                        </Button>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
