<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Flame, Star, Trophy } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import StarRating from '@/components/customer/StarRating.vue';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';
import type { DashboardProductSummary } from '@/types/customerDashboard';

const props = defineProps<{
    topPicks: DashboardProductSummary[];
    trending: DashboardProductSummary[];
    highestRated: DashboardProductSummary[];
}>();

type TabKey = 'picks' | 'trending' | 'rated';

const tab = ref<TabKey>('picks');

const tabs = [
    { key: 'picks' as const, label: 'Top picks', icon: Trophy },
    { key: 'trending' as const, label: 'Trending', icon: Flame },
    { key: 'rated' as const, label: 'Highest rated', icon: Star },
];

const activeList = computed(() => {
    if (tab.value === 'picks') {
        return props.topPicks;
    }

    if (tab.value === 'trending') {
        return props.trending;
    }

    return props.highestRated;
});

const primaryProduct = computed(() => activeList.value[0] ?? null);

const secondaryProducts = computed(() => activeList.value.slice(1, 4));
</script>

<template>
    <section
        class="flex h-full w-full min-h-[420px] flex-col overflow-hidden rounded-3xl border border-border/60 bg-card/80 shadow-sm ring-1 ring-white/5 backdrop-blur-sm dark:bg-card/60 dark:ring-white/[0.04] lg:min-h-0 lg:flex-1"
    >
        <div class="shrink-0 border-b border-border/60 px-4 py-3 text-left sm:px-5">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-muted-foreground">Shoe intelligence</p>
            <h3 class="mt-0.5 text-pretty text-base font-semibold tracking-tight sm:text-lg">What’s hot right now</h3>
            <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-muted-foreground sm:text-sm">
                Visual picks—switch tabs to preview different lists.
            </p>
        </div>

        <div
            class="shrink-0 flex flex-nowrap gap-1.5 overflow-x-auto overscroll-x-contain border-b border-border/50 px-3 py-2.5 [-ms-overflow-style:none] [scrollbar-width:none] sm:px-4 [&::-webkit-scrollbar]:hidden"
            role="tablist"
            aria-label="Product list"
        >
            <button
                v-for="t in tabs"
                :key="t.key"
                type="button"
                role="tab"
                :aria-selected="tab === t.key"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium transition"
                :class="
                    tab === t.key
                        ? 'bg-primary text-primary-foreground shadow-sm'
                        : 'bg-muted/60 text-muted-foreground hover:bg-muted hover:text-foreground'
                "
                @click="tab = t.key"
            >
                <component :is="t.icon" class="size-3.5 shrink-0 opacity-90" />
                {{ t.label }}
            </button>
        </div>

        <div class="flex min-h-0 flex-1 flex-col gap-2.5 overflow-hidden px-3 py-3 sm:px-4 sm:py-3">
            <template v-if="primaryProduct">
                <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain">
                    <Link
                        :href="customer.products.show.url(primaryProduct.slug)"
                        class="group relative block overflow-hidden rounded-2xl border border-border/50 bg-muted/20"
                    >
                        <div
                            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_75%_70%_at_50%_45%,rgba(99,102,241,0.15),transparent_72%)]"
                        />
                        <div class="relative flex h-[168px] max-h-[168px] w-full items-center justify-center sm:h-[176px] sm:max-h-[176px]">
                            <img
                                v-if="primaryProduct.image_url"
                                :src="primaryProduct.image_url"
                                :alt="primaryProduct.name"
                                class="max-h-full max-w-full object-contain px-3 py-4 transition duration-500 group-hover:scale-[1.04]"
                            />
                            <div v-else class="text-xs text-muted-foreground">No image</div>
                        </div>
                        <div
                            class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-background/95 via-background/75 to-transparent px-3 pb-3 pt-8 text-left"
                        >
                            <p class="line-clamp-2 text-pretty text-sm font-semibold leading-snug">{{ primaryProduct.name }}</p>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold tabular-nums">{{ formatPhp(primaryProduct.price) }}</span>
                                <StarRating :rating="Number(primaryProduct.rating)" size="sm" />
                            </div>
                        </div>
                    </Link>

                    <div v-if="secondaryProducts.length" class="mt-2.5 grid grid-cols-3 gap-1.5">
                        <Link
                            v-for="p in secondaryProducts"
                            :key="p.id"
                            :href="customer.products.show.url(p.slug)"
                            class="group flex min-h-0 flex-col overflow-hidden rounded-xl border border-border/50 bg-muted/15 transition hover:border-primary/30 hover:bg-muted/30"
                        >
                            <div class="flex h-[72px] max-h-[72px] items-center justify-center sm:h-[76px] sm:max-h-[76px]">
                                <img
                                    v-if="p.image_url"
                                    :src="p.image_url"
                                    :alt="p.name"
                                    class="max-h-full max-w-full object-contain p-1.5 transition duration-300 group-hover:scale-105"
                                />
                                <div v-else class="text-[10px] text-muted-foreground">—</div>
                            </div>
                            <p
                                class="line-clamp-2 break-words px-1 pb-1.5 text-center text-[10px] font-medium leading-tight text-foreground"
                            >
                                {{ p.name }}
                            </p>
                        </Link>
                    </div>
                </div>
            </template>

            <div
                v-else
                class="flex min-h-[200px] flex-1 flex-col items-center justify-center rounded-2xl border border-dashed border-border/70 py-8 text-center"
            >
                <p class="text-sm text-muted-foreground">No shoes in this list yet.</p>
            </div>

            <Link
                :href="customer.products.url()"
                class="mt-auto block min-h-11 shrink-0 pt-1 text-center text-xs font-medium leading-none text-primary underline-offset-4 transition hover:underline sm:min-h-0"
            >
                View full catalog
            </Link>
        </div>
    </section>
</template>
