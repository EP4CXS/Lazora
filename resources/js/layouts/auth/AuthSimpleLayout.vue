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
const appName = computed(() => page.props.name ?? 'Lazora');
const brandWordmark = computed(() => appName.value.toLowerCase());
const heading = computed(() => props.title ?? '');
const subtext = computed(() => props.description ?? '');
</script>

<template>
    <div
        class="relative min-h-[100dvh] overflow-x-hidden bg-[#050508] text-zinc-100 antialiased selection:bg-orange-500/25"
    >
        <Head>
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Subtle Lazora-aligned atmosphere (matches Welcome hero tone) -->
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_100%_70%_at_50%_-10%,rgba(255,255,255,0.04),transparent_50%)]"
        />
        <div
            class="pointer-events-none fixed inset-x-0 bottom-0 h-[45%] bg-[radial-gradient(ellipse_100%_100%_at_50%_100%,rgba(234,88,12,0.12),transparent_65%)]"
        />

        <header class="relative z-10 flex w-full justify-center px-4 pt-5 sm:pt-8">
            <Link
                :href="home()"
                class="group flex items-center gap-2 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400/40"
            >
                <span
                    class="flex size-9 shrink-0 items-center justify-center rounded-full border border-white/[0.12] bg-white/[0.05] text-white shadow-[0_0_20px_-6px_rgba(234,88,12,0.35)] transition group-hover:border-white/20 sm:size-10"
                >
                    <AppLogoIcon class="size-[22px] text-white sm:size-6" />
                </span>
                <span class="text-[13px] font-semibold lowercase tracking-wide text-white sm:text-sm">{{
                    brandWordmark
                }}</span>
            </Link>
        </header>

        <div
            class="relative z-10 flex flex-col items-center px-4 pb-10 pt-5 sm:px-5 sm:pb-14 sm:pt-8 md:px-6"
        >
            <div class="w-full max-w-[min(100%,22rem)] space-y-5 sm:max-w-[400px] sm:space-y-7">
                <div class="space-y-1.5 text-center sm:space-y-2">
                    <h1
                        class="text-balance text-lg font-semibold tracking-tight text-white sm:text-xl md:text-2xl"
                    >
                        {{ heading }}
                    </h1>
                    <p
                        class="text-pretty text-[13px] leading-relaxed text-zinc-400 sm:text-sm sm:leading-relaxed"
                    >
                        {{ subtext }}
                    </p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>
