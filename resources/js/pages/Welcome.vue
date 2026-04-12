<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { dashboard, home, login, products, register } from '@/routes';

/** Featured hero art — replace `public/images/hero/lazora-hero-feature.png` to update. */
const heroShowcaseSrc = '/images/hero/lazora-hero-feature.png';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage<{ name: string }>();
const appName = computed(() => page.props.name ?? 'Lazora');
const brandWordmark = computed(() => appName.value.toLowerCase());
</script>

<template>
    <div
        class="relative flex min-h-[100dvh] flex-col overflow-x-hidden bg-[#050508] text-zinc-100 antialiased selection:bg-orange-500/30"
    >
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Cinematic base + warm floor glow -->
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_120%_80%_at_50%_-15%,rgba(255,255,255,0.035),transparent_55%)]"
        />
        <div
            class="pointer-events-none fixed inset-x-0 bottom-0 h-[58%] bg-[radial-gradient(ellipse_100%_90%_at_50%_100%,rgba(234,88,12,0.28),rgba(180,50,30,0.1)_48%,transparent_72%)]"
        />
        <div
            class="pointer-events-none fixed inset-x-0 bottom-0 h-[38%] bg-gradient-to-t from-[#1f0a08]/95 via-[#120808]/55 to-transparent"
        />
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_80%_50%_at_70%_20%,rgba(59,130,246,0.04),transparent_55%)]"
        />

        <!-- Header -->
        <header class="relative z-30 shrink-0 px-4 pt-5 sm:px-6 sm:pt-6">
            <div class="mx-auto flex max-w-lg items-center justify-between gap-3 lg:max-w-5xl">
                <Link
                    :href="home()"
                    class="group flex min-w-0 items-center gap-2.5 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400/40"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/[0.1] bg-white/[0.04] shadow-[0_0_24px_-6px_rgba(234,88,12,0.35)]"
                    >
                        <svg
                            class="h-[15px] w-[15px] text-orange-400/95"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                    </span>
                    <span
                        class="truncate text-[15px] font-bold lowercase tracking-wide text-white"
                    >
                        {{ brandWordmark }}
                    </span>
                </Link>

                <nav class="flex shrink-0 items-center gap-2" aria-label="Account">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex min-h-10 items-center justify-center rounded-full border border-white/15 bg-transparent px-4 text-sm font-medium text-white transition hover:bg-white/10"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex min-h-10 min-w-[4.5rem] items-center justify-center rounded-full border border-white/20 bg-transparent px-4 text-sm font-medium text-white transition hover:bg-white/10"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex min-h-10 items-center justify-center rounded-full bg-white px-4 text-sm font-semibold text-zinc-900 shadow-lg shadow-black/25 transition hover:bg-zinc-100"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero: shoe + copy, then CTA vertically centered in space below copy -->
        <main
            class="relative z-10 flex min-h-0 flex-1 flex-col px-4 pb-6 pt-2 sm:px-6 sm:pb-8 sm:pt-4"
            style="perspective: 1400px"
        >
            <div
                class="relative mx-auto flex w-full min-h-0 max-w-lg flex-1 flex-col lg:max-w-4xl"
            >
                <!-- Tilted stage: product + atmosphere only -->
                <div
                    class="relative mx-auto w-full max-w-[26rem] shrink-0 transform-gpu transition-transform duration-500 ease-out will-change-transform [transform:rotate(-2deg)] sm:max-w-[30rem] lg:max-w-2xl lg:[transform:rotate(-1.5deg)]"
                >
                    <!-- Background outline typography -->
                    <div
                        class="pointer-events-none absolute inset-x-0 top-[4%] z-0 flex flex-col items-center gap-0 overflow-hidden opacity-[0.11] sm:top-[2%]"
                        aria-hidden="true"
                    >
                        <span
                            v-for="row in 4"
                            :key="row"
                            class="hero-outline-text font-black uppercase leading-[0.82] tracking-tighter text-transparent"
                        >
                            {{ appName }}
                        </span>
                    </div>

                    <!-- Spotlight + subtle arcs -->
                    <div
                        class="pointer-events-none absolute left-1/2 top-[26%] z-[1] h-[48%] w-[92%] -translate-x-1/2 rounded-full bg-orange-500/[0.07] blur-[56px]"
                    />
                    <div
                        class="pointer-events-none absolute left-1/2 top-[30%] z-[2] h-[38%] w-[78%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.06] blur-[44px]"
                    />
                    <div
                        class="pointer-events-none absolute left-[6%] top-[20%] z-[1] h-36 w-36 rounded-full border border-orange-500/15 opacity-50 sm:left-[10%]"
                    />
                    <div
                        class="pointer-events-none absolute right-[4%] top-[24%] z-[1] h-28 w-44 rotate-12 rounded-[100%] border border-orange-400/12 opacity-40"
                    />

                    <!-- Shoe — larger, centered, object-contain -->
                    <div
                        class="relative z-[5] mx-auto flex w-full items-center justify-center px-1 pt-2 sm:px-2 sm:pt-4"
                    >
                        <img
                            :src="heroShowcaseSrc"
                            :alt="`${appName} featured sneaker`"
                            class="relative h-auto w-full max-w-[min(94vw,26rem)] object-contain drop-shadow-[0_48px_90px_rgba(0,0,0,0.72)] sm:max-w-[30rem] sm:-rotate-[5deg] lg:max-w-[34rem] lg:-rotate-[4deg]"
                            loading="eager"
                            decoding="async"
                            width="800"
                            height="1000"
                        />
                    </div>
                </div>

                <!-- Headline — centered, not tilted -->
                <div
                    class="relative z-[6] mx-auto mt-2 max-w-md shrink-0 px-1 text-center sm:mt-3 sm:px-2 lg:max-w-lg"
                >
                    <h1
                        class="text-balance text-[1.65rem] font-bold uppercase leading-[1.12] tracking-tight text-white sm:text-3xl lg:text-[2.25rem]"
                    >
                        Live your perfect
                    </h1>
                    <p
                        class="mx-auto mt-3 max-w-md text-pretty text-[15px] leading-relaxed text-white/72 sm:text-base"
                    >
                        Smart, gorgeous &amp; fashionable collection makes you cool.
                    </p>
                </div>

                <!-- Explore collection — fills space below copy; link centered between copy and bottom edge -->
                <div
                    class="relative z-[8] mx-auto flex min-h-0 w-full max-w-md flex-1 flex-col items-center justify-center py-5 sm:py-6 lg:max-w-lg"
                >
                    <div
                        class="pointer-events-none absolute inset-x-0 bottom-0 top-1/3 -z-10 bg-gradient-to-t from-orange-600/10 to-transparent blur-xl"
                    />
                    <Link
                        :href="products()"
                        class="relative inline-flex min-h-11 shrink-0 items-center justify-center px-6 text-sm font-semibold tracking-wide text-white transition hover:text-white/95"
                    >
                        <span
                            class="border-b border-white/25 pb-0.5 transition hover:border-white/50"
                        >
                            Explore collection
                        </span>
                    </Link>
                </div>
            </div>

            <p
                class="mx-auto mt-4 hidden max-w-md shrink-0 text-center text-xs text-zinc-500 lg:mt-6 lg:block"
            >
                {{ appName }} — premium footwear for motion and everyday confidence.
            </p>
        </main>
    </div>
</template>

<style scoped>
.hero-outline-text {
    font-size: clamp(2.85rem, 19vw, 6rem);
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.32);
    text-shadow: 0 0 40px rgba(255, 255, 255, 0.04);
}
</style>
