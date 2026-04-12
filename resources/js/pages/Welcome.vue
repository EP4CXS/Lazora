<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ChevronUp } from 'lucide-vue-next';
import { computed } from 'vue';
import { dashboard, home, login, products, register } from '@/routes';

/** Swap `public/images/hero/lazora-hero-showcase.png` to change hero art. */
const heroShowcaseSrc = '/images/hero/lazora-hero-showcase.png';

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
</script>

<template>
    <div
        class="relative min-h-[100dvh] overflow-x-hidden bg-[#0c0c0e] text-zinc-100 antialiased selection:bg-orange-500/30"
    >
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Cinematic base + warm floor glow (reference: charcoal → orange/red) -->
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_120%_80%_at_50%_-20%,rgba(255,255,255,0.04),transparent_50%)]"
        />
        <div
            class="pointer-events-none fixed inset-x-0 bottom-0 h-[55%] bg-[radial-gradient(ellipse_100%_100%_at_50%_100%,rgba(220,90,40,0.35),rgba(180,60,30,0.12)_45%,transparent_70%)]"
        />
        <div
            class="pointer-events-none fixed inset-x-0 bottom-0 h-[35%] bg-gradient-to-t from-[#2a0a06]/90 via-[#1a0808]/40 to-transparent"
        />

        <!-- Premium header: integrated, pill actions -->
        <header class="relative z-30 px-4 pt-5 sm:px-6 sm:pt-6">
            <div class="mx-auto flex max-w-lg items-center justify-between gap-3">
                <Link
                    :href="home()"
                    class="group flex min-w-0 items-center gap-2 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400/40"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/[0.08] bg-white/[0.04] shadow-[0_0_20px_-4px_rgba(251,146,60,0.25)]"
                    >
                        <svg
                            class="h-[15px] w-[15px] text-orange-300/90"
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
                    <span class="truncate text-[15px] font-semibold tracking-wide text-white">
                        {{ appName }}
                    </span>
                </Link>

                <nav class="flex shrink-0 items-center gap-2" aria-label="Account">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex min-h-10 items-center justify-center rounded-full border border-white/10 bg-black/40 px-4 text-sm font-medium text-white backdrop-blur-md transition hover:bg-white/10"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex min-h-10 min-w-[4.5rem] items-center justify-center rounded-full border border-white/[0.08] bg-black/50 px-4 text-sm font-medium text-white/95 backdrop-blur-md transition hover:bg-white/10"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex min-h-10 items-center justify-center rounded-full bg-white px-4 text-sm font-semibold text-zinc-900 shadow-lg shadow-black/20 transition hover:bg-zinc-100"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Tilted immersive hero stage (reference: floating premium card) -->
        <main
            class="relative z-10 mx-auto flex min-h-0 max-w-lg flex-1 flex-col px-4 pb-10 pt-4 sm:px-6 sm:pb-14 sm:pt-6 lg:max-w-5xl lg:pb-16"
            style="perspective: 1200px"
        >
            <div
                class="relative mx-auto w-full origin-top transform-gpu transition-transform duration-500 ease-out will-change-transform [transform:rotateX(2deg)_rotate(-3.5deg)] sm:[transform:rotateX(1deg)_rotate(-3deg)] lg:mx-0 lg:max-w-xl lg:[transform:rotateX(0deg)_rotate(-2deg)]"
            >
                <!-- Large outline typography behind product (reference energy) -->
                <div
                    class="pointer-events-none absolute -left-4 right-0 top-[8%] z-0 flex h-[72%] flex-col items-center justify-start gap-0 overflow-hidden opacity-[0.14] sm:top-[6%]"
                    aria-hidden="true"
                >
                    <span
                        v-for="row in 4"
                        :key="row"
                        class="hero-outline-text font-black uppercase leading-[0.85] tracking-tighter text-transparent"
                    >
                        {{ appName }}
                    </span>
                </div>

                <!-- Motion-style warm accent arcs -->
                <div
                    class="pointer-events-none absolute left-[8%] top-[18%] z-[1] h-40 w-40 rounded-full border border-orange-500/20 opacity-60 blur-[1px] sm:left-[12%]"
                />
                <div
                    class="pointer-events-none absolute right-[5%] top-[22%] z-[1] h-32 w-48 rotate-12 rounded-[100%] border border-orange-400/15 opacity-50"
                />
                <div
                    class="pointer-events-none absolute left-1/2 top-[28%] z-[1] h-64 w-[110%] -translate-x-1/2 rounded-full bg-orange-500/10 blur-3xl"
                />

                <!-- Spotlight on shoe -->
                <div
                    class="pointer-events-none absolute left-1/2 top-[32%] z-[2] h-[42%] w-[85%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/[0.07] blur-[50px]"
                />

                <!-- Shoe -->
                <div
                    class="relative z-[5] flex min-h-[min(48vh,20rem)] items-center justify-center pt-2 sm:min-h-[22rem] lg:min-h-[26rem]"
                >
                    <img
                        :src="heroShowcaseSrc"
                        :alt="`${appName} featured sneaker`"
                        class="relative h-auto w-[92%] max-w-[17.5rem] -rotate-6 object-contain drop-shadow-[0_40px_80px_rgba(0,0,0,0.65)] sm:max-w-[19rem] sm:-rotate-[8deg] lg:max-w-[22rem]"
                        loading="eager"
                        decoding="async"
                        width="800"
                        height="1000"
                    />
                </div>

                <!-- Headline block — sits in warm zone -->
                <div
                    class="relative z-[6] -mt-2 px-1 pb-2 text-left sm:px-2 lg:max-w-md lg:pb-4"
                >
                    <h1
                        class="text-balance text-[1.75rem] font-bold uppercase leading-[1.1] tracking-tight text-white sm:text-3xl lg:text-[2.35rem]"
                    >
                        Live your perfect
                    </h1>
                    <p
                        class="mt-3 max-w-md text-pretty text-[15px] leading-relaxed text-white/75 sm:text-base"
                    >
                        Smart, gorgeous &amp; fashionable collection makes you cool.
                    </p>
                </div>

                <!-- CTA — premium, connected to warm glow -->
                <div
                    class="relative z-[6] mt-6 flex flex-col items-center gap-3 sm:mt-8 lg:items-start"
                >
                    <Link
                        :href="products()"
                        class="group inline-flex min-h-12 w-full max-w-xs items-center justify-center gap-2 rounded-2xl bg-white px-6 text-sm font-semibold text-zinc-900 shadow-[0_12px_40px_-8px_rgba(0,0,0,0.45)] transition hover:bg-zinc-100 active:scale-[0.99] lg:max-w-none lg:px-8"
                    >
                        <span>Get started</span>
                        <ChevronUp
                            class="size-4 transition group-hover:-translate-y-0.5"
                            aria-hidden="true"
                        />
                    </Link>
                    <Link
                        :href="products()"
                        class="text-center text-sm font-medium text-white/70 underline-offset-4 transition hover:text-white hover:underline"
                    >
                        Explore collection
                    </Link>
                </div>
            </div>

            <!-- Desktop: side caption (same story, wider canvas) -->
            <p
                class="mx-auto mt-10 hidden max-w-md text-center text-xs text-zinc-500 lg:mt-12 lg:block"
            >
                {{ appName }} — premium footwear, crafted for motion and everyday confidence.
            </p>
        </main>
    </div>
</template>

<style scoped>
.hero-outline-text {
    font-size: clamp(2.75rem, 18vw, 5.5rem);
    -webkit-text-stroke: 1px rgba(255, 255, 255, 0.35);
    text-shadow: 0 0 40px rgba(255, 255, 255, 0.05);
}
</style>
