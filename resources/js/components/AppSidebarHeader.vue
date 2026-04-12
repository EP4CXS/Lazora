<script setup lang="ts">
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger, useSidebar } from '@/components/ui/sidebar';
import type { BreadcrumbItem } from '@/types';

const props = withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
        /** When true, breadcrumb text is shown only below the `md` breakpoint (mobile / narrow). */
        mobileBreadcrumbsOnly?: boolean;
        /** Compact page label (e.g. customer shell) when the sidebar is icon-collapsed or on mobile. */
        contextTitle?: string | null;
    }>(),
    {
        breadcrumbs: () => [],
        mobileBreadcrumbsOnly: false,
        contextTitle: null,
    },
);

const { isMobile, state } = useSidebar();

const showCompactContextTitle = computed(
    () => Boolean(props.contextTitle?.trim()) && (isMobile.value || state.value === 'collapsed'),
);

const showBreadcrumbs = computed(() => {
    if (!props.breadcrumbs?.length) {
        return false;
    }

    if (showCompactContextTitle.value) {
        return false;
    }

    return true;
});
</script>

<template>
    <header
        class="flex h-16 min-w-0 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex min-w-0 flex-1 items-center gap-2">
            <SidebarTrigger class="-ml-1 shrink-0" />
            <span
                v-if="showCompactContextTitle"
                class="min-w-0 truncate text-sm font-semibold tracking-tight text-foreground"
            >
                {{ contextTitle }}
            </span>
            <template v-if="showBreadcrumbs">
                <div :class="mobileBreadcrumbsOnly ? 'md:hidden' : 'min-w-0'">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </div>
            </template>
        </div>
    </header>
</template>
