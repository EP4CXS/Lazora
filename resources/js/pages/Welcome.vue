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

        <!-- Hero: centered column — background type, shoe, copy, CTA -->
        <main
            class="relative z-10 flex min-h-0 flex-1 flex-col items-center px-4 pb-6 pt-1 sm:px-6 sm:pb-8 sm:pt-3 md:px-8 md:pt-4"
            style="perspective: 1400px"
        >
            <div
                class="relative flex w-full min-h-0 max-w-lg flex-1 flex-col items-center md:max-w-2xl lg:max-w-4xl"
            >
                <!-- Product stage: symmetric on mobile (no tilt), subtle tilt sm+ -->
                <div
                    class="relative mx-auto w-full max-w-[min(100%,26rem)] shrink-0 transform-gpu transition-transform duration-500 ease-out will-change-transform max-sm:[transform:none] sm:max-w-[30rem] sm:[transform:rotate(-2deg)] lg:max-w-[min(100%,36rem)] lg:[transform:rotate(-1.25deg)]"
                >
                    <!-- LAZORA outline — centered stack, stays behind shoe -->
                    <div
                        class="pointer-events-none absolute left-1/2 top-[2%] z-0 flex w-full max-w-[100vw] -translate-x-1/2 flex-col items-center gap-0 overflow-hidden opacity-[0.085] sm:top-[3%] sm:opacity-[0.11] md:top-[4%]"
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

                    <!-- Spotlight (centered) + light rim accents -->
                    <div
                        class="pointer-events-none absolute left-1/2 top-[28%] z-[1] h-[50%] w-[min(94%,22rem)] -translate-x-1/2 rounded-full bg-orange-500/[0.065] blur-[52px] sm:top-[26%] sm:w-[90%] sm:blur-[56px]"
                    />
                    <div
                        class="pointer-events-none absolute left-1/2 top-[32%] z-[2] h-[40%] w-[min(88%,20rem)] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.055] blur-[42px] sm:w-[78%]"
                    />
                    <div
                        class="pointer-events-none absolute left-[8%] top-[20%] z-[1] hidden h-36 w-36 rounded-full border border-orange-500/14 opacity-50 sm:block sm:left-[10%]"
                    />
                    <div
                        class="pointer-events-none absolute right-[5%] top-[23%] z-[1] hidden h-28 w-44 rotate-12 rounded-[100%] border border-orange-400/12 opacity-40 sm:block"
                    />

                    <!-- Shoe — centered, object-contain; rotation only sm+ -->
                    <div
                        class="relative z-[5] mx-auto flex w-full justify-center px-2 pt-3 sm:px-3 sm:pt-5 md:pt-6"
                    >
                        <img
                            :src="heroShowcaseSrc"
                            :alt="`${appName} featured sneaker`"
                            class="relative mx-auto h-auto w-full max-w-[min(92vw,26rem)] object-contain drop-shadow-[0_44px_88px_rgba(0,0,0,0.75)] max-sm:rotate-0 sm:max-w-[30rem] sm:-rotate-[4deg] lg:max-w-[min(92vw,34rem)] lg:-rotate-[3deg]"
                            loading="eager"
                            decoding="async"
                            width="800"
                            height="1000"
                        />
                    </div>
                </div>

                <!-- Headline -->
                <div
                    class="relative z-[6] mx-auto mt-4 w-full max-w-md shrink-0 px-2 text-center sm:mt-5 md:mt-6 lg:max-w-lg"
                >
                    <h1
                        class="text-balance text-[1.6rem] font-bold uppercase leading-[1.12] tracking-tight text-white sm:text-3xl md:text-[2rem] lg:text-[2.25rem]"
                    >
                        Live your perfect
                    </h1>
                    <p
                        class="mx-auto mt-2.5 max-w-md text-pretty text-[15px] leading-relaxed text-white/72 sm:mt-3 sm:text-base"
                    >
                        Smart, gorgeous &amp; fashionable collection makes you cool.
                    </p>
                </div>

                <!-- CTA — vertical center in remaining space -->
                <div
                    class="relative z-[8] mx-auto flex min-h-0 w-full max-w-md flex-1 flex-col items-center justify-center py-4 sm:min-h-[5rem] sm:py-6 md:min-h-[5.5rem] lg:max-w-lg"
                >
                    <div
                        class="pointer-events-none absolute inset-x-0 bottom-0 top-1/4 -z-10 bg-gradient-to-t from-orange-600/10 to-transparent blur-xl"
                    />
                    <Link
                        :href="products()"
                        class="relative inline-flex min-h-[44px] shrink-0 items-center justify-center px-6 text-sm font-semibold tracking-wide text-white transition hover:text-white/95"
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
                class="mx-auto mt-3 hidden max-w-md shrink-0 text-center text-xs text-zinc-500 md:mt-5 lg:mt-6 lg:block"
            >
                {{ appName }} — premium footwear for motion and everyday confidence.
            </p>
        </main>
    </div>
</template>

<style scoped>
.hero-outline-text {
    font-size: clamp(2.4rem, 16vw, 6rem);
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.28);
    text-shadow: 0 0 40px rgba(255, 255, 255, 0.04);
}
</style>
