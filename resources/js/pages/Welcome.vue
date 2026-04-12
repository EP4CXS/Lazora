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
        <header class="relative z-30 shrink-0 px-4 pb-1 pt-4 sm:px-6 sm:pb-2 sm:pt-5">
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

        <!-- Hero: shoe + headline vertically centered in viewport; CTA anchored below -->
        <main
            class="relative z-10 flex min-h-0 flex-1 flex-col px-4 pb-5 pt-1 sm:px-6 sm:pb-6 sm:pt-2 md:px-8"
        >
            <!-- Grows to fill space below header; justify-center places shoe block in middle-center -->
            <div
                class="mx-auto flex w-full min-h-0 max-w-lg flex-1 flex-col items-center justify-center md:max-w-2xl lg:max-w-4xl"
            >
                <!--
                  Visual frame: outline + shoe share one box; LAZORA absolute inset-0 centered.
                -->
                <div
                    class="hero-visual-frame relative mx-auto w-full max-w-[min(100%,26rem)] shrink-0 sm:max-w-[30rem] lg:max-w-[min(100%,36rem)]"
                >
                    <!-- LAZORA — centered behind product (same origin as shoe) -->
                    <div
                        class="pointer-events-none absolute inset-0 z-0 flex items-center justify-center overflow-hidden"
                        aria-hidden="true"
                    >
                        <div
                            class="flex flex-col items-center justify-center opacity-[0.09] sm:opacity-[0.11] md:opacity-[0.12]"
                        >
                            <span
                                v-for="row in 3"
                                :key="row"
                                class="hero-outline-text font-black uppercase tracking-tighter text-transparent"
                            >
                                {{ appName }}
                            </span>
                        </div>
                    </div>

                    <!-- Spotlights: centered on frame -->
                    <div
                        class="pointer-events-none absolute left-1/2 top-1/2 z-[1] h-[min(72%,20rem)] w-[min(92%,22rem)] -translate-x-1/2 -translate-y-1/2 rounded-full bg-orange-500/[0.07] blur-[56px] sm:h-[min(68%,22rem)] sm:w-[90%]"
                    />
                    <div
                        class="pointer-events-none absolute left-1/2 top-1/2 z-[2] h-[min(58%,17rem)] w-[min(78%,18rem)] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.055] blur-[44px]"
                    />
                    <div
                        class="pointer-events-none absolute left-[6%] top-[18%] z-[1] hidden h-32 w-32 rounded-full border border-orange-500/14 opacity-45 sm:block sm:left-[8%] sm:top-[16%] md:h-36 md:w-36"
                    />
                    <div
                        class="pointer-events-none absolute right-[4%] top-[20%] z-[1] hidden h-24 w-40 rotate-12 rounded-[100%] border border-orange-400/11 opacity-38 sm:block sm:right-[5%]"
                    />

                    <!-- Shoe — centered in frame; rotation on image only -->
                    <div
                        class="relative z-[5] flex w-full items-center justify-center px-3 py-3 sm:px-4 sm:py-4 md:py-5"
                    >
                        <img
                            :src="heroShowcaseSrc"
                            :alt="`${appName} featured sneaker`"
                            class="mx-auto h-auto w-full max-w-[min(88vw,24rem)] object-contain object-center drop-shadow-[0_40px_80px_rgba(0,0,0,0.78)] max-sm:rotate-0 sm:max-w-[28rem] sm:-rotate-[3.5deg] lg:max-w-[min(90vw,32rem)] lg:-rotate-[3deg]"
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
            </div>

            <!-- CTA — fixed to lower area without stealing flex space from centered hero -->
            <div
                class="relative z-[8] mx-auto flex w-full max-w-md shrink-0 flex-col items-center pb-1 pt-3 sm:pb-2 sm:pt-4 lg:max-w-lg"
            >
                <div
                    class="pointer-events-none absolute inset-x-0 bottom-0 top-0 -z-10 bg-gradient-to-t from-orange-600/10 to-transparent opacity-80 blur-xl"
                />
                <Link
                    :href="products()"
                    class="relative inline-flex min-h-[44px] items-center justify-center px-6 text-sm font-semibold tracking-wide text-white transition hover:text-white/95"
                >
                    <span
                        class="border-b border-white/25 pb-0.5 transition hover:border-white/50"
                    >
                        Explore collection
                    </span>
                </Link>
            </div>

            <p
                class="mx-auto mt-2 hidden max-w-md shrink-0 text-center text-xs text-zinc-500 lg:mt-3 lg:block"
            >
                {{ appName }} — premium footwear for motion and everyday confidence.
            </p>
        </main>
    </div>
</template>

<style scoped>
/* Stacked outline word — sized to frame the product; centered via parent flex */
.hero-outline-text {
    font-size: clamp(2.85rem, 21vw, 7.25rem);
    line-height: 0.76;
    letter-spacing: -0.035em;
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.26);
    text-shadow: 0 0 48px rgba(255, 255, 255, 0.035);
}

.hero-outline-text + .hero-outline-text {
    margin-top: -0.06em;
}
</style>
