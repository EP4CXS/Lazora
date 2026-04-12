<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, Package, ShoppingCart } from 'lucide-vue-next';
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
    useSidebar,
} from '@/components/ui/sidebar';
import customer from '@/routes/customer';
import type { NavItem } from '@/types';

const { isMobile, setOpenMobile } = useSidebar();

function handleLogoNavClick(): void {
    if (isMobile.value) {
        setOpenMobile(false);
    }
}

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: customer.dashboard.url(),
        icon: LayoutGrid,
    },
    {
        title: 'Products',
        href: customer.products.url(),
        icon: Package,
    },
    {
        title: 'My Orders',
        href: customer.orders.index.url(),
        icon: ShoppingCart,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
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
                        <Link :href="customer.dashboard.url()" @click="handleLogoNavClick">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" close-mobile-sidebar-on-navigate />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
