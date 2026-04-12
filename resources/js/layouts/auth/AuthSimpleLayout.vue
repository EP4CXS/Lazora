<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const props = defineProps<{
    title?: string;
    description?: string;
}>();

const page = usePage<{ name: string }>();
const appName = computed(() => page.props.name ?? 'Store');
const heading = computed(() => props.title ?? '');
const subtext = computed(() => props.description ?? '');
</script>

<template>
    <div class="min-h-screen bg-black text-zinc-100 antialiased">
        <Head>
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <header class="flex w-full justify-center px-4 pt-8 sm:pt-10">
            <Link
                :href="home()"
                class="group flex items-center gap-2 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40"
            >
                <span
                    class="flex size-10 items-center justify-center rounded-lg border border-white/15 bg-white/[0.06] text-white transition group-hover:border-white/25 group-hover:bg-white/[0.1]"
                >
                    <AppLogoIcon class="size-6 text-white" />
                </span>
                <span class="text-sm font-semibold tracking-tight text-white">{{ appName }}</span>
            </Link>
        </header>

        <div class="flex flex-col items-center px-4 pb-14 pt-8 sm:pb-16 sm:pt-10">
            <div class="w-full max-w-[400px] space-y-8">
                <div class="space-y-2 text-center">
                    <h1 class="text-balance text-xl font-semibold tracking-tight text-white sm:text-2xl">
                        {{ heading }}
                    </h1>
                    <p class="text-pretty text-sm leading-relaxed text-zinc-400">
                        {{ subtext }}
                    </p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
