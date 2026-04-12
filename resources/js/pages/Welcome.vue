<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { formatPhp } from '@/lib/currency';
import { dashboard, home, login, products, register } from '@/routes';

/** Replace `public/images/hero/lazora-hero-showcase.png` to swap hero art. */
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
const appName = computed(() => page.props.name ?? 'Store');
const demoFromPrice = formatPhp(89);
</script>

<template>
    <div
        class="relative min-h-screen overflow-x-hidden bg-[#06060a] text-zinc-100 antialiased"
    >
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <!-- Ambient luxury backdrop -->
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_100%_70%_at_50%_-15%,rgba(99,102,241,0.22),transparent_55%)]"
        />
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_60%_45%_at_85%_55%,rgba(251,146,60,0.06),transparent_50%)]"
        />
        <div
            class="pointer-events-none fixed inset-0 bg-[radial-gradient(ellipse_50%_40%_at_10%_80%,rgba(59,130,246,0.08),transparent_45%)]"
        />
        <div
            class="pointer-events-none fixed inset-0 bg-[linear-gradient(to_bottom,rgba(6,6,10,0),rgba(6,6,10,0.85)_85%)]"
        />

        <section class="relative flex min-h-screen flex-col">
            <!-- Premium header -->
            <header
                class="relative z-20 border-b border-white/[0.06] bg-[#06060a]/75 px-4 pb-3 pt-4 backdrop-blur-xl sm:px-6 sm:pb-3.5 sm:pt-5 lg:px-10 lg:pt-6"
            >
                <div
                    class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3"
                >
                    <Link
                        :href="home()"
                        class="group flex min-w-0 items-center gap-2.5 rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/50"
                    >
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-white/[0.08] bg-white/[0.04] shadow-[0_0_24px_-4px_rgba(99,102,241,0.35)] ring-1 ring-white/[0.06] transition group-hover:border-white/15 group-hover:bg-white/[0.06]"
                        >
                            <svg
                                class="h-[18px] w-[18px] text-indigo-400"
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
                            class="truncate text-[15px] font-semibold tracking-[0.02em] text-white sm:text-base"
                        >
                            {{ appName }}
                        </span>
                    </Link>

                    <nav
                        class="flex shrink-0 items-center justify-end gap-1.5 sm:gap-2"
                        aria-label="Account"
                    >
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="inline-flex min-h-[2.75rem] items-center justify-center rounded-2xl border border-white/[0.1] bg-white/[0.05] px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-white/[0.04] transition hover:border-white/20 hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/50"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="inline-flex min-h-[2.75rem] min-w-[4.25rem] items-center justify-center rounded-2xl px-3.5 py-2 text-sm font-medium text-zinc-300 transition hover:bg-white/[0.05] hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/40"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="inline-flex min-h-[2.75rem] items-center justify-center rounded-2xl border border-white/20 bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-[0_8px_30px_-8px_rgba(255,255,255,0.25)] transition hover:border-white hover:bg-zinc-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/60"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </header>

            <div
                class="relative z-10 flex flex-1 flex-col px-4 pb-16 pt-6 sm:px-6 sm:pb-20 sm:pt-8 lg:px-10 lg:pb-28"
            >
                <div
                    class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-12 lg:gap-16"
                >
                    <!-- Mobile-first: hero product first, then copy -->
                    <div
                        class="grid flex-1 items-center gap-10 lg:grid-cols-12 lg:gap-12 xl:gap-14"
                    >
                        <!-- Featured product card — immersive hero -->
                        <div
                            class="order-1 flex justify-center lg:col-span-7 lg:justify-center xl:col-span-7"
                        >
                            <div
                                class="relative w-full max-w-[22rem] select-none sm:max-w-md lg:max-w-none lg:max-w-[28rem]"
                            >
                                <!-- Outer glow -->
                                <div
                                    class="pointer-events-none absolute -inset-3 rounded-[2rem] bg-gradient-to-b from-indigo-500/15 via-transparent to-orange-500/10 opacity-90 blur-2xl"
                                />
                                <div
                                    class="pointer-events-none absolute -bottom-8 left-1/2 h-24 w-[85%] -translate-x-1/2 rounded-full bg-black/50 blur-3xl"
                                />

                                <div
                                    class="relative overflow-hidden rounded-[1.75rem] border border-white/[0.09] bg-gradient-to-br from-zinc-900/95 via-[#0c0c12] to-[#08080f] p-4 shadow-[0_32px_64px_-24px_rgba(0,0,0,0.85),inset_0_1px_0_rgba(255,255,255,0.06)] ring-1 ring-white/[0.04] sm:rounded-[2rem] sm:p-5"
                                >
                                    <!-- Card top sheen -->
                                    <div
                                        class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"
                                    />

                                    <div
                                        class="flex items-start justify-between gap-3 border-b border-white/[0.06] pb-4"
                                    >
                                        <div>
                                            <p
                                                class="text-[10px] font-semibold uppercase tracking-[0.22em] text-zinc-500"
                                            >
                                                Featured
                                            </p>
                                            <p
                                                class="mt-1.5 text-lg font-semibold tracking-tight text-white sm:text-xl"
                                            >
                                                Signature line
                                            </p>
                                        </div>
                                        <span
                                            class="shrink-0 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-semibold tracking-wide text-emerald-300/95"
                                        >
                                            In stock
                                        </span>
                                    </div>

                                    <!-- Shoe stage -->
                                    <div class="relative mt-4">
                                        <div
                                            class="relative flex min-h-[min(58vh,19.5rem)] flex-col items-center justify-center overflow-hidden rounded-2xl border border-white/[0.05] bg-gradient-to-b from-zinc-800/40 via-[#0a0a10] to-indigo-950/30 sm:min-h-[21rem] lg:min-h-[23rem]"
                                        >
                                            <!-- Watermark typography -->
                                            <span
                                                class="pointer-events-none absolute left-1/2 top-[42%] z-0 -translate-x-1/2 -translate-y-1/2 select-none whitespace-nowrap text-[clamp(3.5rem,18vw,7rem)] font-bold uppercase leading-none tracking-tighter text-white/[0.03]"
                                                aria-hidden="true"
                                            >
                                                {{ appName }}
                                            </span>

                                            <!-- Glow stack -->
                                            <div
                                                class="pointer-events-none absolute left-1/2 top-[38%] h-[48%] w-[92%] max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/30 blur-[56px]"
                                            />
                                            <div
                                                class="pointer-events-none absolute left-1/2 top-[30%] h-[26%] w-[48%] -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-400/20 blur-[40px]"
                                            />
                                            <div
                                                class="pointer-events-none absolute bottom-[8%] left-1/2 h-8 w-[62%] max-w-xs -translate-x-1/2 rounded-full bg-orange-400/10 blur-2xl"
                                            />
                                            <div
                                                class="pointer-events-none absolute bottom-[6%] left-1/2 h-6 w-[55%] -translate-x-1/2 rounded-full bg-black/60 blur-xl"
                                            />

                                            <div
                                                class="pointer-events-none absolute inset-[5%] rounded-xl ring-1 ring-inset ring-white/[0.04]"
                                            />

                                            <img
                                                :src="heroShowcaseSrc"
                                                alt="Featured Lazora sneaker"
                                                class="relative z-10 mx-auto h-auto w-[88%] max-w-[17.5rem] object-contain object-center drop-shadow-[0_32px_56px_rgba(0,0,0,0.55)] sm:max-w-[19rem] lg:max-w-[21rem]"
                                                loading="eager"
                                                decoding="async"
                                                width="800"
                                                height="1000"
                                            />
                                        </div>

                                        <!-- Meta: mobile-friendly grid -->
                                        <div class="mt-4 grid grid-cols-2 gap-2.5">
                                            <div
                                                class="rounded-2xl border border-white/[0.06] bg-white/[0.03] p-3.5 sm:p-4"
                                            >
                                                <p
                                                    class="text-[11px] font-medium text-zinc-500"
                                                >
                                                    Est. delivery
                                                </p>
                                                <p
                                                    class="mt-1 text-sm font-semibold text-white"
                                                >
                                                    2–4 business days
                                                </p>
                                            </div>
                                            <div
                                                class="rounded-2xl border border-white/[0.07] bg-gradient-to-br from-white/[0.06] to-transparent p-3.5 shadow-inner sm:p-4"
                                            >
                                                <div
                                                    class="flex items-end justify-between gap-2"
                                                >
                                                    <div>
                                                        <p
                                                            class="text-[11px] font-medium text-zinc-500"
                                                        >
                                                            From
                                                        </p>
                                                        <p
                                                            class="mt-0.5 text-2xl font-semibold tabular-nums tracking-tight text-white"
                                                        >
                                                            {{ demoFromPrice }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="flex gap-1.5"
                                                        aria-hidden="true"
                                                    >
                                                        <span
                                                            class="h-6 w-6 rounded-full border border-white/10 bg-zinc-800 shadow-inner"
                                                        />
                                                        <span
                                                            class="h-6 w-6 rounded-full border border-white/10 bg-red-700/90 shadow-inner"
                                                        />
                                                        <span
                                                            class="h-6 w-6 rounded-full border border-dashed border-white/20"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editorial + CTA — centered on mobile -->
                        <div
                            class="order-2 flex flex-col justify-center text-center lg:col-span-5 lg:text-left"
                        >
                            <p
                                class="text-[11px] font-semibold uppercase tracking-[0.28em] text-indigo-300/90"
                            >
                                Premium footwear
                            </p>
                            <h1
                                class="mt-3 text-balance text-[1.65rem] font-semibold leading-[1.2] tracking-tight text-white sm:text-3xl lg:text-4xl lg:leading-[1.15]"
                            >
                                Find your next signature pair
                            </h1>
                            <p
                                class="mx-auto mt-4 max-w-md text-pretty text-[15px] leading-relaxed text-zinc-400 sm:text-base lg:mx-0"
                            >
                                Fast delivery, trusted checkout, and a calm flow
                                built for confident everyday style.
                            </p>

                            <div
                                class="mx-auto mt-8 flex w-full max-w-md flex-col gap-3 sm:max-w-none sm:flex-row sm:justify-center lg:mx-0 lg:justify-start"
                            >
                                <Link
                                    :href="products()"
                                    class="inline-flex min-h-[3.25rem] w-full items-center justify-center rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-500 to-violet-600 px-6 py-3.5 text-center text-[15px] font-semibold text-white shadow-[0_16px_40px_-12px_rgba(99,102,241,0.55)] ring-1 ring-white/15 transition hover:brightness-110 hover:shadow-[0_20px_44px_-10px_rgba(99,102,241,0.5)] focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300/80 focus-visible:ring-offset-2 focus-visible:ring-offset-[#06060a] active:scale-[0.99] sm:min-w-[11rem]"
                                >
                                    Shop Now
                                </Link>
                                <Link
                                    :href="products()"
                                    class="inline-flex min-h-[3.25rem] w-full items-center justify-center rounded-2xl border border-white/[0.12] bg-white/[0.04] px-6 py-3.5 text-center text-[15px] font-semibold text-white backdrop-blur-sm transition hover:border-white/20 hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-white/25 focus-visible:ring-offset-2 focus-visible:ring-offset-[#06060a] sm:min-w-[11rem]"
                                >
                                    Explore Products
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Lower inspiration — editorial strip -->
                    <section
                        aria-labelledby="welcome-inspiration-heading"
                        class="relative mx-auto w-full max-w-3xl rounded-[1.75rem] border border-white/[0.06] bg-gradient-to-b from-white/[0.04] to-transparent px-6 py-10 text-center sm:px-10 sm:py-12 lg:max-w-none lg:px-12"
                    >
                        <div
                            class="pointer-events-none absolute inset-0 rounded-[1.75rem] bg-[radial-gradient(ellipse_80%_60%_at_50%_0%,rgba(99,102,241,0.12),transparent_65%)]"
                        />
                        <div class="relative">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-[0.3em] text-indigo-300/85"
                            >
                                New season · Premium selection
                            </p>
                            <h2
                                id="welcome-inspiration-heading"
                                class="mx-auto mt-4 max-w-xl text-balance text-2xl font-semibold leading-snug tracking-tight text-white sm:text-3xl lg:text-[2rem] lg:leading-tight"
                            >
                                Elevate every day with curated essentials
                            </h2>
                            <p
                                class="mx-auto mt-5 max-w-lg text-pretty text-[15px] leading-relaxed text-zinc-400 sm:text-base"
                            >
                                Discover thoughtfully chosen pieces, seamless
                                checkout, and delivery you can trust—built for
                                a calm, confident shopping experience.
                            </p>
                            <Link
                                :href="products()"
                                class="mt-8 inline-flex min-h-[3rem] items-center justify-center gap-2 rounded-2xl border border-indigo-400/30 bg-indigo-500/10 px-8 py-3 text-sm font-semibold text-indigo-100 transition hover:border-indigo-400/50 hover:bg-indigo-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/50"
                            >
                                View collection
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                                    />
                                </svg>
                            </Link>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
</template>
