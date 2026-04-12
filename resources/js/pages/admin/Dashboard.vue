<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, ShoppingBag } from 'lucide-vue-next';
import admin, { dashboard } from '@/routes/admin';

defineProps<{
    orderStats: {
        total: number;
        pendingConfirmation: number;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Admin Dashboard',
                href: dashboard.url(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="flex h-full flex-col gap-6 p-4">
        <div class="grid gap-4 xl:grid-cols-3">
            <div class="rounded-3xl border border-sidebar-border/70 bg-card p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase text-muted-foreground">Users</p>
                <p class="mt-4 text-3xl font-semibold">—</p>
                <p class="mt-2 text-sm text-muted-foreground">Reporting coming soon</p>
            </div>
            <div class="rounded-3xl border border-sidebar-border/70 bg-card p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase text-muted-foreground">Revenue</p>
                <p class="mt-4 text-3xl font-semibold tabular-nums">—</p>
                <p class="mt-2 text-sm text-muted-foreground">Connect payouts to see totals</p>
            </div>
            <div class="rounded-3xl border border-sidebar-border/70 bg-card p-6 shadow-sm">
                <p class="text-sm font-semibold uppercase text-muted-foreground">Orders</p>
                <p class="mt-4 text-3xl font-semibold tabular-nums">{{ orderStats.total }}</p>
                <p class="mt-2 text-sm text-muted-foreground">All customer orders in the system</p>
            </div>
        </div>

        <div
            class="flex flex-col gap-4 rounded-3xl border border-sidebar-border/70 bg-card p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="flex items-start gap-4">
                <div
                    class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary"
                >
                    <ShoppingBag class="size-6" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Order activity</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        <span class="font-semibold tabular-nums text-foreground">{{
                            orderStats.pendingConfirmation
                        }}</span>
                        order<span v-if="orderStats.pendingConfirmation !== 1">s</span> awaiting your confirmation.
                    </p>
                </div>
            </div>
            <Link
                :href="admin.orders.index.url()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground shadow-sm transition hover:bg-primary/90"
            >
                View orders
                <ArrowRight class="size-4" />
            </Link>
        </div>

        <div class="rounded-3xl border border-sidebar-border/70 bg-card p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Admin workspace</h2>
            <p class="mt-2 text-sm text-muted-foreground">
                Use the sidebar to manage products, review customer orders, and handle security setting.
            </p>
        </div>
    </div>
</template>
