<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardFeaturedHero from '@/components/customer/dashboard/DashboardFeaturedHero.vue';
import DashboardInsightsPanel from '@/components/customer/dashboard/DashboardInsightsPanel.vue';
import DashboardRecentProducts from '@/components/customer/dashboard/DashboardRecentProducts.vue';
import type { DashboardProductSummary } from '@/types/customerDashboard';

defineProps<{
    spotlight: DashboardProductSummary | null;
    topPicks: DashboardProductSummary[];
    trending: DashboardProductSummary[];
    highestRated: DashboardProductSummary[];
    recentProducts: DashboardProductSummary[];
}>();
</script>

<template>
    <Head title="Customer Dashboard" />

    <div
        class="mx-auto flex w-full min-w-0 max-w-[1600px] flex-col gap-5 px-4 pb-12 pt-1 sm:gap-6 sm:px-5 sm:pb-14 lg:gap-7 lg:px-6 xl:px-8"
    >
        <header class="space-y-2 text-left">
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-muted-foreground">Welcome back</p>
            <h1 class="text-pretty text-xl font-semibold tracking-tight text-foreground sm:text-2xl md:text-3xl">
                Your shoe shop hub
            </h1>
            <p class="max-w-prose text-pretty text-sm leading-relaxed text-muted-foreground sm:text-base">
                Discover featured footwear, see what’s trending, and jump back into the pairs shoppers love most.
            </p>
        </header>

        <div class="grid min-h-0 grid-cols-1 gap-5 md:gap-6 lg:grid-cols-12 lg:items-stretch lg:gap-6 lg:min-h-[500px]">
            <div class="flex min-h-0 min-w-0 lg:col-span-8">
                <DashboardFeaturedHero :product="spotlight" />
            </div>
            <div class="flex min-h-0 min-w-0 lg:col-span-4">
                <DashboardInsightsPanel
                    :top-picks="topPicks"
                    :trending="trending"
                    :highest-rated="highestRated"
                />
            </div>
        </div>

        <DashboardRecentProducts :products="recentProducts" />
    </div>
</template>
