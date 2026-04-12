<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

const props = withDefaults(
    defineProps<{
        items: NavItem[];
        /** Close the mobile sheet after navigating (customer drawer UX). */
        closeMobileSidebarOnNavigate?: boolean;
    }>(),
    {
        closeMobileSidebarOnNavigate: false,
    },
);

const { isCurrentUrl } = useCurrentUrl();
const { isMobile, setOpenMobile } = useSidebar();

function handleMainNavClick(): void {
    if (!props.closeMobileSidebarOnNavigate || !isMobile.value) {
        return;
    }

    setOpenMobile(false);
}
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                >
                    <Link :href="item.href" class="flex w-full items-center gap-2" @click="handleMainNavClick">
                        <component :is="item.icon" class="shrink-0" />
                        <span class="flex-1 truncate text-left">{{ item.title }}</span>
                        <span
                            v-if="item.badgeCount != null && item.badgeCount > 0"
                            class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-destructive px-1.5 text-[10px] font-bold leading-none text-destructive-foreground tabular-nums"
                        >
                            {{ item.badgeCount > 99 ? '99+' : item.badgeCount }}
                        </span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
