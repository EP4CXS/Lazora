<script setup lang="ts">
import { computed } from 'vue';
import MobileProductRowCarousel from '@/components/customer/products/MobileProductRowCarousel.vue';
import type { CustomerProductListItem } from '@/types/customerProductList';

const props = defineProps<{
    products: CustomerProductListItem[];
}>();

const emit = defineEmits<{
    'open-details': [product: CustomerProductListItem];
    'open-quick-add': [product: CustomerProductListItem];
}>();

/** First horizontal band (roughly first half of the list), second band gets the remainder. */
const rowOneProducts = computed(() => {
    const list = props.products;
    const n = list.length;

    if (n === 0) {
        return [];
    }

    const mid = Math.ceil(n / 2);

    return list.slice(0, mid);
});

const rowTwoProducts = computed(() => {
    const list = props.products;
    const n = list.length;

    if (n === 0) {
        return [];
    }

    const mid = Math.ceil(n / 2);

    return list.slice(mid);
});
</script>

<template>
    <div class="flex min-w-0 flex-col gap-5">
        <MobileProductRowCarousel
            :products="rowOneProducts"
            row-label="Top row"
            @open-details="emit('open-details', $event)"
            @open-quick-add="emit('open-quick-add', $event)"
        />
        <MobileProductRowCarousel
            v-if="rowTwoProducts.length"
            :products="rowTwoProducts"
            row-label="Bottom row"
            @open-details="emit('open-details', $event)"
            @open-quick-add="emit('open-quick-add', $event)"
        />
    </div>
</template>
