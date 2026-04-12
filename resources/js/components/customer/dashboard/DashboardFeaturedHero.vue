<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Sparkles } from 'lucide-vue-next';
import StarRating from '@/components/customer/StarRating.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';
import type { DashboardProductSummary } from '@/types/customerDashboard';

defineProps<{
    product: DashboardProductSummary | null;
}>();
</script>

<template>
    <section
        class="relative flex h-full w-full min-h-[420px] flex-col overflow-hidden rounded-3xl border border-border/60 bg-gradient-to-br from-card via-card to-muted/30 shadow-sm ring-1 ring-white/5 dark:from-card dark:via-card dark:to-muted/20 dark:ring-white/[0.04] lg:min-h-0 lg:flex-1"
    >
        <div
            class="pointer-events-none absolute -right-20 -top-20 size-72 rounded-full bg-primary/10 blur-3xl dark:bg-primary/15"
        />
        <div
            class="pointer-events-none absolute -bottom-24 -left-16 size-64 rounded-full bg-indigo-500/5 blur-3xl dark:bg-indigo-400/10"
        />

        <div
            v-if="product"
            class="relative grid min-h-0 flex-1 gap-6 p-5 sm:gap-8 sm:p-7 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.95fr)] lg:items-center lg:gap-8 xl:gap-10"
        >
            <div class="flex min-h-0 min-w-0 flex-col justify-center gap-3 text-left sm:gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <Badge
                        variant="secondary"
                        class="rounded-full border border-primary/20 bg-primary/10 px-3 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-primary"
                    >
                        Featured pick
                    </Badge>
                    <Badge variant="outline" class="rounded-full text-[10px] uppercase tracking-wide">
                        {{ product.category }}
                    </Badge>
                </div>
                <h2 class="text-balance text-2xl font-semibold tracking-tight sm:text-3xl lg:text-4xl">
                    {{ product.name }}
                </h2>
                <p class="line-clamp-3 max-w-md text-pretty text-sm leading-relaxed text-muted-foreground sm:text-base">
                    Hand-picked for style and comfort—premium materials, responsive cushioning, and a silhouette that works on court or street.
                </p>
                <div class="flex min-w-0 flex-wrap items-center gap-3 sm:gap-4">
                    <p class="text-2xl font-semibold tabular-nums tracking-tight sm:text-3xl">
                        {{ formatPhp(product.price) }}
                    </p>
                    <div class="flex min-w-0 items-center gap-2">
                        <StarRating :rating="Number(product.rating)" size="sm" />
                        <span class="text-sm tabular-nums text-muted-foreground">
                            {{ Number(product.rating).toFixed(1) }}
                        </span>
                    </div>
                </div>
                <div class="flex w-full flex-col gap-2.5 pt-1 sm:w-auto sm:flex-row sm:flex-wrap sm:gap-3">
                    <Button as-child class="w-full justify-center rounded-xl shadow-sm transition hover:shadow-md sm:w-auto">
                        <Link :href="customer.products.show.url(product.slug)">
                            View details
                            <ArrowRight class="ml-2 size-4 shrink-0" />
                        </Link>
                    </Button>
                    <Button variant="outline" as-child class="w-full justify-center rounded-xl border-primary/25 sm:w-auto">
                        <Link :href="customer.products.url()">Browse catalog</Link>
                    </Button>
                </div>
            </div>

            <Link
                :href="customer.products.show.url(product.slug)"
                class="group relative mx-auto flex h-[220px] max-h-[260px] w-full max-w-lg items-center justify-center overflow-hidden rounded-2xl border border-border/50 bg-muted/20 sm:h-[260px] sm:max-h-[300px] lg:h-full lg:max-h-[min(100%,280px)] lg:min-h-[220px]"
            >
                <div
                    class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_70%_at_50%_55%,rgba(99,102,241,0.14),transparent_72%)] opacity-90"
                />
                <div
                    class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 bg-[radial-gradient(ellipse_85%_75%_at_50%_50%,rgba(99,102,241,0.22),transparent_75%)]"
                />
                <img
                    v-if="product.image_url"
                    :src="product.image_url"
                    :alt="product.name"
                    class="relative z-[1] max-h-full max-w-full object-contain px-4 py-6 transition duration-500 ease-out group-hover:scale-[1.04] drop-shadow-[0_20px_50px_rgba(0,0,0,0.45)] dark:drop-shadow-[0_24px_60px_rgba(0,0,0,0.55)] sm:px-6"
                />
                <div
                    v-else
                    class="relative z-[1] flex size-full min-h-[160px] items-center justify-center text-sm text-muted-foreground"
                >
                    No image
                </div>
            </Link>
        </div>

        <div
            v-else
            class="relative flex flex-1 flex-col items-center justify-center gap-4 px-6 py-12 text-center sm:px-8"
        >
            <Sparkles class="size-10 text-primary/80" />
            <h2 class="text-pretty text-xl font-semibold tracking-tight sm:text-2xl">Discover your next pair</h2>
            <p class="max-w-md text-pretty text-sm leading-relaxed text-muted-foreground sm:text-base">
                New drops are added regularly. Explore the catalog to find performance and lifestyle footwear in your size.
            </p>
            <Button as-child class="w-full max-w-xs justify-center rounded-xl sm:w-auto">
                <Link :href="customer.products.url()">Shop products</Link>
            </Button>
        </div>
    </section>
</template>
