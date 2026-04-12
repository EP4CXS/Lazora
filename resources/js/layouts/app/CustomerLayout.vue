<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import CustomerSidebar from '@/components/CustomerSidebar.vue';
import { Toaster } from '@/components/ui/sonner';
import { resolveCustomerPageTitle } from '@/lib/customerPageTitle';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

const contextTitle = computed(() => resolveCustomerPageTitle(page.url));
</script>

<template>
    <AppShell variant="sidebar">
        <CustomerSidebar variant="inset" />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" :context-title="contextTitle" />
            <slot />
        </AppContent>
        <Toaster />
    </AppShell>
</template>

