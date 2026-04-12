<script setup lang="ts">
import type { LinkComponentBaseProps, Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

type Props = {
    href: LinkComponentBaseProps['href'];
    tabindex?: number;
    method?: Method;
    as?: string;
    class?: HTMLAttributes['class'];
};

const props = defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :tabindex="tabindex"
        :method="method"
        :as="as"
        :class="
            cn(
                'font-medium text-zinc-700 underline decoration-zinc-300/80 underline-offset-4 transition-colors duration-200 ease-out hover:text-zinc-900 hover:decoration-zinc-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:text-zinc-300 dark:decoration-zinc-600 dark:hover:text-white dark:focus-visible:ring-offset-zinc-950',
                props.class,
            )
        "
    >
        <slot />
    </Link>
</template>
