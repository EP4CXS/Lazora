<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, LayoutGrid, Package, ShieldCheck, ShoppingBag } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, products } from '@/routes';
import admin from '@/routes/admin';
import type { NavItem } from '@/types';

type SharedProps = {
    adminPendingOrdersCount?: number;
};

const page = usePage<{ adminPendingOrdersCount?: number } & Record<string, unknown>>();

const pendingOrders = computed(
    () => (page.props as SharedProps).adminPendingOrdersCount ?? 0,
);

const mainNavItems = computed<NavItem[]>(() => [
    {
        title: 'Admin Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Products',
        href: products(),
        icon: Package,
    },
    {
        title: 'Orders',
        href: admin.orders.index.url(),
        icon: ShoppingBag,
        badgeCount: pendingOrders.value,
    },
    {
        title: 'Security',
        href: '#',
        icon: ShieldCheck,
    },
]);

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
