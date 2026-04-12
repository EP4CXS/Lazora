<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useResizeObserver } from '@vueuse/core';
import { ChevronLeft, ChevronRight, Package } from 'lucide-vue-next';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import StarRating from '@/components/customer/StarRating.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';
import type { DashboardProductSummary } from '@/types/customerDashboard';

const props = defineProps<{
    products: DashboardProductSummary[];
}>();

const viewportRef = ref<HTMLElement | null>(null);

const scrollMetrics = ref({
    scrollLeft: 0,
    scrollWidth: 0,
    clientWidth: 0,
});

function refreshScrollMetrics(): void {
    const el = viewportRef.value;

    if (!el) {
        return;
    }

    scrollMetrics.value = {
        scrollLeft: el.scrollLeft,
        scrollWidth: el.scrollWidth,
        clientWidth: el.clientWidth,
    };
}

const maxScroll = computed(() =>
    Math.max(0, scrollMetrics.value.scrollWidth - scrollMetrics.value.clientWidth),
);

const canScroll = computed(() => maxScroll.value > 1);

const canGoPrev = computed(() => scrollMetrics.value.scrollLeft > 1);

const canGoNext = computed(
    () => scrollMetrics.value.scrollLeft < maxScroll.value - 1,
);

/** One "page" of the carousel ≈ viewport width; used for dots and arrow step. */
const pageCount = computed(() => {
    const { clientWidth, scrollWidth } = scrollMetrics.value;

    if (scrollWidth <= 0 || clientWidth <= 0) {
        return 1;
    }

    return Math.max(1, Math.ceil(scrollWidth / clientWidth));
});

const activePage = computed(() => {
    const { scrollLeft, clientWidth } = scrollMetrics.value;

    if (!canScroll.value || clientWidth <= 0) {
        return 0;
    }

    const idx = Math.floor((scrollLeft + clientWidth / 2) / clientWidth);

    return Math.min(pageCount.value - 1, Math.max(0, idx));
});

const cardShells = [
    'from-violet-500/[0.12] via-card to-card dark:from-violet-400/[0.08]',
    'from-sky-500/[0.12] via-card to-card dark:from-sky-400/[0.08]',
    'from-amber-500/[0.10] via-card to-card dark:from-amber-400/[0.07]',
    'from-emerald-500/[0.11] via-card to-card dark:from-emerald-400/[0.07]',
];

function colorLabels(color: string | null): string[] {
    if (!color) {
        return [];
    }

    return color
        .split(/[/,]/)
        .map((s) => s.trim())
        .filter(Boolean)
        .slice(0, 4);
}

function swatchStyle(label: string, index: number): { backgroundColor: string } {
    let hash = 0;

    for (let i = 0; i < label.length; i++) {
        hash = label.charCodeAt(i) + ((hash << 5) - hash);
    }

    const hue = Math.abs(hash + index * 47) % 360;

    return { backgroundColor: `hsl(${hue} 42% 52%)` };
}

function onScroll(): void {
    refreshScrollMetrics();
}

function scrollByViewport(direction: -1 | 1): void {
    const el = viewportRef.value;

    if (!el) {
        return;
    }

    const w = el.clientWidth;
    const left = el.scrollLeft;
    const max = Math.max(0, el.scrollWidth - el.clientWidth);

    const delta =
        direction > 0
            ? Math.min(w, max - left)
            : Math.min(w, left);

    if (delta <= 0) {
        return;
    }

    el.scrollBy({ left: direction * delta, behavior: 'smooth' });
}

function goPrev(): void {
    scrollByViewport(-1);
}

function goNext(): void {
    scrollByViewport(1);
}

function goToPage(pageIndex: number): void {
    const el = viewportRef.value;

    if (!el) {
        return;
    }

    const max = Math.max(0, el.scrollWidth - el.clientWidth);
    const pages = Math.max(1, pageCount.value);
    const target =
        pages <= 1 ? 0 : (max / Math.max(1, pages - 1)) * Math.min(pageIndex, pages - 1);

    el.scrollTo({ left: Math.min(max, target), behavior: 'smooth' });
}

onMounted(() => {
    viewportRef.value?.addEventListener('scroll', onScroll, { passive: true });
    void nextTick(() => refreshScrollMetrics());
});

onUnmounted(() => {
    viewportRef.value?.removeEventListener('scroll', onScroll);
});

useResizeObserver(viewportRef, () => {
    refreshScrollMetrics();
});

watch(
    () => props.products,
    () => {
        void nextTick(() => {
            viewportRef.value?.scrollTo({ left: 0, behavior: 'auto' });
            refreshScrollMetrics();
        });
    },
    { deep: true },
);
</script>

<template>
    <section
        class="flex min-w-0 flex-col gap-4 sm:gap-5"
        role="region"
        aria-roledescription="carousel"
        aria-label="Recent and fresh arrivals"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div class="min-w-0 text-left">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-muted-foreground">Popular shoes</p>
                <h2 class="text-pretty text-xl font-semibold tracking-tight sm:text-2xl">Recent & fresh arrivals</h2>
                <p class="mt-1 max-w-prose text-pretty text-sm leading-relaxed text-muted-foreground sm:text-base">
                    Swipe the row on mobile, or use arrows and dots on larger screens. Tap a card for details.
                </p>
            </div>
            <Link
                :href="customer.products.url()"
                class="inline-flex min-h-11 shrink-0 items-center justify-center self-start text-sm font-medium text-primary underline-offset-4 transition hover:underline sm:min-h-0 sm:self-auto"
            >
                See all products
            </Link>
        </div>

        <div v-if="products.length" class="flex min-w-0 flex-col gap-3">
            <div class="flex items-stretch gap-2 sm:gap-3">
                <Button
                    v-if="canScroll"
                    type="button"
                    variant="outline"
                    size="icon"
                    class="hidden size-10 shrink-0 self-center rounded-full border-border/70 bg-background/90 shadow-sm backdrop-blur-sm sm:inline-flex"
                    :disabled="!canGoPrev"
                    aria-label="Previous products"
                    @click="goPrev"
                >
                    <ChevronLeft class="size-5" />
                </Button>

                <!-- Viewport: horizontal scroll + touch swipe; snap aligns cards after gestures -->
                <div
                    ref="viewportRef"
                    class="min-w-0 flex-1 touch-pan-x overflow-x-auto overflow-y-hidden overscroll-x-contain scroll-smooth [-ms-overflow-style:none] [scrollbar-width:none] snap-x snap-proximity [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden"
                >
                    <div class="flex flex-nowrap gap-3 pb-1 sm:gap-4">
                        <article
                            v-for="(p, i) in products"
                            :key="p.id"
                            class="group flex h-full min-h-[26rem] w-[min(18.25rem,calc(100vw-2.25rem))] shrink-0 snap-start snap-always flex-col overflow-hidden rounded-2xl border border-border/50 bg-gradient-to-br shadow-sm ring-1 ring-black/[0.03] transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/25 hover:shadow-lg hover:shadow-primary/10 sm:min-h-[28rem] sm:w-[18rem] md:w-[18.5rem] dark:ring-white/[0.04]"
                            :class="cardShells[i % cardShells.length]"
                        >
                            <Link
                                :href="customer.products.show.url(p.slug)"
                                class="relative flex h-[200px] shrink-0 items-center justify-center overflow-hidden bg-black/5 sm:h-[220px] dark:bg-white/[0.03]"
                            >
                                <div
                                    class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_72%_at_50%_55%,rgba(99,102,241,0.12),transparent_70%)]"
                                />
                                <div
                                    class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100 bg-[radial-gradient(ellipse_85%_78%_at_50%_50%,rgba(99,102,241,0.18),transparent_74%)]"
                                />
                                <img
                                    v-if="p.image_url"
                                    :src="p.image_url"
                                    :alt="p.name"
                                    class="relative z-[1] max-h-full max-w-full object-contain px-4 py-4 transition duration-500 ease-out group-hover:scale-[1.06] drop-shadow-[0_16px_40px_rgba(0,0,0,0.38)] dark:drop-shadow-[0_20px_48px_rgba(0,0,0,0.55)]"
                                />
                                <div
                                    v-else
                                    class="relative z-[1] flex size-full items-center justify-center text-xs text-muted-foreground"
                                >
                                    No image
                                </div>
                                <div class="absolute left-2 top-2 z-[2] flex flex-wrap gap-1 sm:left-3 sm:top-3">
                                    <Badge
                                        v-if="i < 3"
                                        variant="secondary"
                                        class="rounded-full border-0 bg-background/90 px-2 py-0.5 text-[10px] font-semibold uppercase backdrop-blur"
                                    >
                                        New
                                    </Badge>
                                    <Badge
                                        v-if="p.is_featured"
                                        variant="secondary"
                                        class="rounded-full border-0 bg-amber-500/90 px-2 py-0.5 text-[10px] font-semibold uppercase text-white backdrop-blur dark:bg-amber-600/90"
                                    >
                                        Hot
                                    </Badge>
                                </div>
                            </Link>

                            <div
                                class="flex min-h-[14.5rem] flex-1 flex-col gap-2 border-t border-border/40 bg-card/40 p-3 text-left sm:min-h-[15rem] sm:gap-3 sm:p-4 dark:bg-card/25"
                            >
                                <Badge variant="outline" class="w-fit rounded-lg text-[10px] uppercase tracking-wide">
                                    {{ p.category }}
                                </Badge>
                                <Link
                                    :href="customer.products.show.url(p.slug)"
                                    class="group/title min-h-[2.25rem] sm:min-h-[2.5rem]"
                                >
                                    <h3
                                        class="line-clamp-2 text-pretty text-sm font-semibold leading-snug tracking-tight transition group-hover/title:text-primary sm:text-base"
                                    >
                                        {{ p.name }}
                                    </h3>
                                </Link>
                                <div class="flex flex-wrap items-center gap-2">
                                    <StarRating :rating="Number(p.rating)" size="sm" />
                                    <span class="text-xs tabular-nums text-muted-foreground">{{
                                        Number(p.rating).toFixed(1)
                                    }}</span>
                                </div>
                                <div v-if="colorLabels(p.color).length" class="flex flex-wrap items-center gap-2">
                                    <span class="text-[10px] font-medium uppercase tracking-wide text-muted-foreground"
                                        >Colors</span
                                    >
                                    <span class="flex gap-1.5">
                                        <span
                                            v-for="(label, ci) in colorLabels(p.color)"
                                            :key="ci"
                                            class="size-2.5 shrink-0 rounded-full ring-2 ring-background shadow-sm"
                                            :title="label"
                                            :style="swatchStyle(label, ci)"
                                        />
                                    </span>
                                </div>
                                <div
                                    class="mt-auto flex min-w-0 flex-wrap items-baseline justify-between gap-x-2 gap-y-1 border-t border-border/40 pt-2 sm:pt-3"
                                >
                                    <div class="flex min-w-0 flex-wrap items-baseline gap-2">
                                        <span class="text-base font-semibold tabular-nums sm:text-lg">{{
                                            formatPhp(p.price)
                                        }}</span>
                                        <span
                                            v-if="p.compare_at_price"
                                            class="text-xs text-muted-foreground line-through tabular-nums sm:text-sm"
                                        >
                                            {{ formatPhp(p.compare_at_price) }}
                                        </span>
                                    </div>
                                    <span
                                        class="text-[10px] font-medium sm:text-xs"
                                        :class="p.stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive'"
                                    >
                                        {{ p.stock > 0 ? `${p.stock} left` : 'Out of stock' }}
                                    </span>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <Button
                    v-if="canScroll"
                    type="button"
                    variant="outline"
                    size="icon"
                    class="hidden size-10 shrink-0 self-center rounded-full border-border/70 bg-background/90 shadow-sm backdrop-blur-sm sm:inline-flex"
                    :disabled="!canGoNext"
                    aria-label="Next products"
                    @click="goNext"
                >
                    <ChevronRight class="size-5" />
                </Button>
            </div>

            <div
                v-if="canScroll"
                class="flex justify-center gap-2 pt-1 sm:hidden"
                role="tablist"
                aria-label="Carousel navigation"
            >
                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    class="size-10 rounded-full border-border/70 bg-background/90 shadow-sm"
                    :disabled="!canGoPrev"
                    aria-label="Previous products"
                    @click="goPrev"
                >
                    <ChevronLeft class="size-5" />
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    class="size-10 rounded-full border-border/70 bg-background/90 shadow-sm"
                    :disabled="!canGoNext"
                    aria-label="Next products"
                    @click="goNext"
                >
                    <ChevronRight class="size-5" />
                </Button>
            </div>

            <div
                v-if="canScroll && pageCount > 1"
                class="flex justify-center gap-2 pt-1"
                role="tablist"
                aria-label="Carousel pages"
            >
                <button
                    v-for="pageIdx in pageCount"
                    :key="pageIdx"
                    type="button"
                    role="tab"
                    :aria-selected="pageIdx - 1 === activePage"
                    :aria-label="`Page ${pageIdx} of ${pageCount}`"
                    class="size-2 rounded-full transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                    :class="
                        pageIdx - 1 === activePage
                            ? 'scale-125 bg-primary shadow-sm'
                            : 'bg-muted-foreground/35 hover:bg-muted-foreground/55'
                    "
                    @click="goToPage(pageIdx - 1)"
                />
            </div>
        </div>

        <div
            v-else
            class="flex flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-border/80 bg-muted/10 py-16 text-center"
        >
            <Package class="size-10 text-muted-foreground/60" />
            <p class="text-sm text-muted-foreground">No products available yet. Check back soon.</p>
            <Link :href="customer.products.url()" class="text-sm font-medium text-primary hover:underline">Browse catalog</Link>
        </div>
    </section>
</template>
