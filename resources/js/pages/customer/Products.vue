<script setup lang="ts">
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { Package, Search, ShoppingBag, Zap } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import MobileProductGridCarousel from '@/components/customer/products/MobileProductGridCarousel.vue';
import ProductDetailsSheet from '@/components/customer/products/ProductDetailsSheet.vue';
import StarRating from '@/components/customer/StarRating.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import CustomerLayout from '@/layouts/app/CustomerLayout.vue';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';
import type { CustomerProductListItem } from '@/types/customerProductList';

type PaginationLink = { url: string | null; label: string; active: boolean };

type Paginated = {
    data: CustomerProductListItem[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
};

const props = defineProps<{
    filters: Record<string, unknown>;
    categories: string[];
    products: Paginated;
}>();

defineOptions({
    layout: CustomerLayout,
});

const search = ref(String(props.filters.search ?? ''));
const category = ref(String(props.filters.category ?? ''));
const sort = ref(String(props.filters.sort ?? 'newest'));

const detailProduct = ref<CustomerProductListItem | null>(null);
const detailSheetOpen = ref(false);

function applyFilters(): void {
    router.get(
        customer.products.url({
            query: {
                search: search.value || undefined,
                category: category.value || undefined,
                sort: sort.value || undefined,
            },
        }),
        {},
        { preserveState: true, replace: true },
    );
}

watch([category, sort], () => applyFilters());

function excerpt(text: string | null): string {
    if (!text) {
        return 'Premium quality. See full details for more.';
    }

    return text.length > 120 ? `${text.slice(0, 120).trim()}…` : text;
}

function openProductDetails(product: CustomerProductListItem): void {
    detailProduct.value = product;
    detailSheetOpen.value = true;
}

function openProductQuickAdd(product: CustomerProductListItem): void {
    openProductDetails(product);
}

function defaultCartSize(product: CustomerProductListItem): string {
    return product.available_sizes[0] ?? '37';
}
</script>

<template>
    <Head title="Products" />

    <div class="flex flex-col gap-6 p-4 pb-12 sm:gap-7">
        <div
            class="relative overflow-hidden rounded-2xl border border-border/70 bg-gradient-to-br from-card via-card to-muted/20 p-5 shadow-sm sm:p-8"
        >
            <div
                class="pointer-events-none absolute -right-12 -top-12 size-48 rounded-full bg-primary/5 blur-3xl"
            />
            <div class="relative flex flex-col gap-4 text-left sm:flex-row sm:items-end sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-primary/80">Footwear</p>
                    <h1 class="mt-1 text-pretty text-2xl font-semibold tracking-tight sm:text-3xl">Premium shoe shop</h1>
                    <p class="mt-2 max-w-xl text-pretty text-sm leading-relaxed text-muted-foreground sm:text-base">
                        Curated performance and lifestyle silhouettes—live inventory, fair pricing, and studio photography
                        for every pair.
                    </p>
                </div>
                <div class="flex shrink-0 items-center gap-2 text-sm text-muted-foreground">
                    <Package class="size-4 shrink-0" />
                    <span>{{ products.data.length }} on this page</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
            <div class="relative min-w-0 flex-1 sm:min-w-[200px] sm:max-w-md">
                <Search class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    v-model="search"
                    class="h-10 rounded-xl border-border/80 pl-9"
                    placeholder="Search name or category…"
                    @keyup.enter="applyFilters"
                />
            </div>
            <div class="flex flex-wrap gap-2">
                <select
                    v-model="category"
                    class="h-10 rounded-xl border border-input bg-background px-3 text-sm shadow-xs outline-none focus-visible:border-ring"
                >
                    <option value="">All categories</option>
                    <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
                </select>
                <select
                    v-model="sort"
                    class="h-10 rounded-xl border border-input bg-background px-3 text-sm shadow-xs outline-none focus-visible:border-ring"
                >
                    <option value="newest">Newest</option>
                    <option value="featured">Featured</option>
                    <option value="price_asc">Price ↑</option>
                    <option value="price_desc">Price ↓</option>
                </select>
                <Button type="button" variant="secondary" class="rounded-xl" @click="applyFilters"> Search </Button>
            </div>
        </div>

        <div
            v-if="products.data.length === 0"
            class="rounded-2xl border border-dashed border-border/80 py-16 text-center text-sm text-muted-foreground"
        >
            No products match your filters yet.
        </div>

        <template v-else>
            <!-- Mobile: 2-column grid per slide, horizontal swipe -->
            <div class="sm:hidden">
                <MobileProductGridCarousel
                    :products="products.data"
                    @open-details="openProductDetails"
                    @open-quick-add="openProductQuickAdd"
                />
            </div>

            <!-- Desktop / tablet: full product cards -->
            <div class="hidden grid-cols-1 gap-5 sm:grid sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                <article
                    v-for="p in products.data"
                    :key="p.id"
                    class="group flex flex-col overflow-hidden rounded-2xl border border-border/70 bg-card/60 text-left shadow-sm ring-0 transition duration-300 ease-out hover:-translate-y-1.5 hover:border-primary/25 hover:shadow-xl hover:shadow-[0_20px_50px_-12px_rgba(99,102,241,0.18)]"
                >
                    <Link
                        :href="customer.products.show.url(p.slug)"
                        class="relative block aspect-[4/3] overflow-hidden rounded-t-2xl"
                    >
                        <div
                            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_72%_at_50%_58%,rgba(99,102,241,0.11),transparent_68%)] opacity-90 dark:bg-[radial-gradient(ellipse_82%_74%_at_50%_56%,rgba(99,102,241,0.16),transparent_70%)]"
                        />
                        <div
                            class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 bg-[radial-gradient(ellipse_85%_78%_at_50%_52%,rgba(99,102,241,0.2),transparent_72%)]"
                        />
                        <img
                            v-if="p.image_url"
                            :src="p.image_url"
                            :alt="p.name"
                            class="relative z-[1] mx-auto size-full max-h-full object-contain object-center px-4 py-6 transition duration-500 ease-out drop-shadow-[0_12px_32px_rgba(0,0,0,0.38)] group-hover:scale-[1.05] group-hover:drop-shadow-[0_22px_52px_rgba(0,0,0,0.5)] dark:drop-shadow-[0_16px_42px_rgba(0,0,0,0.55)] sm:px-5 sm:py-7"
                        />
                        <div
                            v-else
                            class="relative z-[1] flex size-full items-center justify-center bg-muted/15 text-xs text-muted-foreground"
                        >
                            No image
                        </div>
                        <div
                            v-if="p.is_featured"
                            class="absolute left-3 top-3 rounded-full bg-background/90 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-primary shadow-sm backdrop-blur"
                        >
                            Featured
                        </div>
                    </Link>

                    <div class="flex flex-1 flex-col gap-3 p-4 sm:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <Badge variant="outline" class="rounded-lg border-border/80 text-[10px] uppercase tracking-wider">
                                {{ p.category }}
                            </Badge>
                            <StarRating :rating="Number(p.rating)" />
                        </div>

                        <Link
                            :href="customer.products.show.url(p.slug)"
                            class="line-clamp-2 text-base font-semibold leading-snug tracking-tight transition group-hover:text-primary"
                        >
                            {{ p.name }}
                        </Link>

                        <p class="line-clamp-2 text-sm leading-relaxed text-muted-foreground">
                            {{ excerpt(p.description) }}
                        </p>

                        <div
                            v-if="p.color || p.sizes"
                            class="flex flex-col gap-1.5 text-xs text-muted-foreground"
                        >
                            <p v-if="p.color">
                                <span class="font-medium text-foreground/80">Color:</span>
                                {{ p.color }}
                            </p>
                            <p v-if="p.sizes">
                                <span class="font-medium text-foreground/80">Sizes:</span>
                                {{ p.sizes }}
                            </p>
                        </div>

                        <div class="flex items-baseline justify-between gap-2 border-t border-border/60 pt-3">
                            <p class="text-xl font-semibold tabular-nums">{{ formatPhp(p.price) }}</p>
                            <span
                                class="text-xs font-medium"
                                :class="p.stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive'"
                            >
                                {{ p.stock > 0 ? `${p.stock} in stock` : 'Out of stock' }}
                            </span>
                        </div>

                        <div class="mt-auto flex flex-col gap-2 sm:flex-row">
                            <Form v-bind="customer.cart.store.form()" #default="{ processing }" class="flex-1">
                                <input type="hidden" name="product_id" :value="p.id" />
                                <input type="hidden" name="quantity" value="1" />
                                <input type="hidden" name="size" :value="defaultCartSize(p)" />
                                <Button
                                    type="submit"
                                    variant="outline"
                                    class="w-full rounded-xl border-primary/20 transition hover:border-primary/50"
                                    :disabled="processing || p.stock < 1"
                                >
                                    <ShoppingBag class="mr-2 size-4" />
                                    Add to cart
                                </Button>
                            </Form>
                            <Form v-bind="customer.orders.store.form()" #default="{ processing }" class="flex-1">
                                <input type="hidden" name="product_id" :value="p.id" />
                                <input type="hidden" name="quantity" value="1" />
                                <Button type="submit" class="w-full rounded-xl" :disabled="processing || p.stock < 1">
                                    <Zap class="mr-2 size-4" />
                                    Order now
                                </Button>
                            </Form>
                        </div>

                        <Button variant="ghost" size="sm" class="w-full rounded-xl text-muted-foreground" as-child>
                            <Link :href="customer.products.show.url(p.slug)">View details</Link>
                        </Button>
                    </div>
                </article>
            </div>
        </template>

        <ProductDetailsSheet v-model:open="detailSheetOpen" :product="detailProduct" />

        <nav v-if="products.last_page > 1" class="flex flex-wrap justify-center gap-1 pt-2">
            <template v-for="(link, i) in products.links" :key="i">
                <Button
                    v-if="link.url"
                    variant="outline"
                    size="sm"
                    class="rounded-lg text-xs sm:text-sm"
                    :class="{ 'border-primary/40 bg-primary/5': link.active }"
                    as-child
                >
                    <Link :href="link.url" preserve-scroll
                        ><span v-html="link.label"></span
                    ></Link>
                </Button>
                <span
                    v-else
                    class="flex items-center px-2 py-1 text-xs text-muted-foreground sm:px-3 sm:text-sm"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
