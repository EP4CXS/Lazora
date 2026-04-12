<script setup lang="ts">
import { useResizeObserver } from '@vueuse/core';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import MobileProductCard from '@/components/customer/products/MobileProductCard.vue';
import type { CustomerProductListItem } from '@/types/customerProductList';

const props = defineProps<{
    products: CustomerProductListItem[];
    /** Accessible label for dots (e.g. "Top row"). */
    rowLabel: string;
}>();

const emit = defineEmits<{
    'open-details': [product: CustomerProductListItem];
    'open-quick-add': [product: CustomerProductListItem];
}>();

const PRODUCTS_PER_SLIDE = 2;

const viewportRef = ref<HTMLElement | null>(null);

const scrollMetrics = ref({
    scrollLeft: 0,
    scrollWidth: 0,
    clientWidth: 0,
});

const slides = computed(() => {
    const list = props.products;
    const chunks: CustomerProductListItem[][] = [];

    for (let i = 0; i < list.length; i += PRODUCTS_PER_SLIDE) {
        chunks.push(list.slice(i, i + PRODUCTS_PER_SLIDE));
    }

    return chunks;
});

function refreshMetrics(): void {
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

const activeSlide = computed(() => {
    const { scrollLeft, clientWidth } = scrollMetrics.value;

    if (!canScroll.value || clientWidth <= 0) {
        return 0;
    }

    return Math.min(
        slides.value.length - 1,
        Math.max(0, Math.round(scrollLeft / clientWidth)),
    );
});

function onScroll(): void {
    refreshMetrics();
}

function goToSlide(index: number): void {
    const el = viewportRef.value;

    if (!el) {
        return;
    }

    const w = el.clientWidth;
    el.scrollTo({ left: index * w, behavior: 'smooth' });
}

onMounted(() => {
    viewportRef.value?.addEventListener('scroll', onScroll, { passive: true });
    void nextTick(() => refreshMetrics());
});

onUnmounted(() => {
    viewportRef.value?.removeEventListener('scroll', onScroll);
});

useResizeObserver(viewportRef, () => {
    refreshMetrics();
});

watch(
    () => props.products,
    () => {
        void nextTick(() => {
            viewportRef.value?.scrollTo({ left: 0, behavior: 'auto' });
            refreshMetrics();
        });
    },
    { deep: true },
);
</script>

<template>
    <div v-if="products.length" class="flex min-w-0 flex-col gap-2">
        <div
            ref="viewportRef"
            class="flex min-w-0 w-full flex-nowrap touch-pan-x overflow-x-auto overflow-y-hidden overscroll-x-contain [-ms-overflow-style:none] [scrollbar-width:none] snap-x snap-mandatory [-webkit-overflow-scrolling:touch] scroll-smooth [&::-webkit-scrollbar]:hidden"
        >
            <div
                v-for="(pair, slideIdx) in slides"
                :key="slideIdx"
                class="box-border w-full min-w-full shrink-0 grow-0 snap-start snap-always px-0.5"
            >
                <div class="grid grid-cols-2 gap-2.5">
                    <MobileProductCard
                        v-for="p in pair"
                        :key="p.id"
                        :product="p"
                        @details="emit('open-details', p)"
                        @quick-add="emit('open-quick-add', p)"
                    />
                </div>
            </div>
        </div>

        <div
            v-if="canScroll && slides.length > 1"
            class="flex justify-center gap-1.5 pt-0.5"
            role="tablist"
            :aria-label="`${rowLabel} pages`"
        >
            <button
                v-for="(_, i) in slides"
                :key="i"
                type="button"
                role="tab"
                :aria-selected="i === activeSlide"
                :aria-label="`${rowLabel} page ${i + 1} of ${slides.length}`"
                class="size-1.5 rounded-full transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background"
                :class="
                    i === activeSlide
                        ? 'scale-125 bg-primary shadow-sm'
                        : 'bg-muted-foreground/35 hover:bg-muted-foreground/55'
                "
                @click="goToSlide(i)"
            />
        </div>
    </div>
</template>
