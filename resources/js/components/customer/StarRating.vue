<script setup lang="ts">
import { computed } from 'vue';
import { Star } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        rating: number;
        size?: 'sm' | 'md';
    }>(),
    { size: 'sm' },
);

const fullStars = computed(() => Math.min(5, Math.max(0, Math.round(props.rating))));

const sizeClass = computed(() => (props.size === 'md' ? 'size-4' : 'size-3.5'));
</script>

<template>
    <div
        class="flex items-center gap-0.5 text-amber-400"
        role="img"
        :aria-label="`Rating ${rating} out of 5`"
    >
        <Star
            v-for="i in 5"
            :key="i"
            :class="[
                sizeClass,
                i <= fullStars ? 'fill-current text-amber-400' : 'fill-transparent text-muted-foreground/25',
            ]"
            stroke-width="1.5"
        />
    </div>
</template>
