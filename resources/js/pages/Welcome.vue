<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { formatPhp } from '@/lib/currency';
import { dashboard, home, login, products, register } from '@/routes';

/** Hero product art — replace `public/images/hero/lazora-hero-showcase.png` to swap the shoe. */
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
        class="relative min-h-screen overflow-x-hidden bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100"
    >
        <Head title="Welcome">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <section
            class="relative flex min-h-screen flex-col bg-[radial-gradient(ellipse_120%_80%_at_50%_-20%,rgba(99,102,241,0.12),transparent)] dark:bg-[radial-gradient(ellipse_120%_80%_at_50%_-20%,rgba(99,102,241,0.18),transparent)]"
        >
            <div
                class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_bottom,transparent,rgba(24,24,27,0.02))] dark:bg-[linear-gradient(to_bottom,transparent,rgba(0,0,0,0.25))]"
            />

            <header
                class="relative z-10 flex w-full flex-wrap items-center justify-between gap-3 px-4 pb-2 pt-5 sm:gap-4 sm:px-6 sm:pt-6 lg:px-10 lg:pt-8"
            >
                <Link
                    :href="home()"
                    class="group flex min-w-0 items-center gap-2.5 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40"
                >
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-zinc-200/80 bg-white shadow-sm ring-1 ring-black/[0.04] transition group-hover:border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:ring-white/[0.06]"
                    >
                        <svg
                            class="h-4 w-4 text-indigo-600 dark:text-indigo-400"
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
                        class="truncate text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-50 sm:text-lg"
                    >
                        {{ appName }}
                    </span>
                </Link>

                <nav
                    class="flex shrink-0 flex-wrap items-center justify-end gap-2 sm:gap-2.5"
                    aria-label="Account"
                >
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-sm font-medium text-zinc-800 shadow-sm ring-1 ring-black/[0.04] transition duration-200 ease-out hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md hover:shadow-zinc-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 active:translate-y-0 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:ring-white/[0.06] dark:hover:border-zinc-600 dark:hover:shadow-black/20"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex min-h-[2.5rem] min-w-[4.5rem] items-center justify-center rounded-xl border border-transparent px-3.5 py-2 text-sm font-medium text-zinc-700 transition duration-200 ease-out hover:-translate-y-0.5 hover:border-zinc-200 hover:bg-white hover:shadow-md hover:shadow-zinc-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 active:translate-y-0 dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:bg-zinc-900/80 dark:hover:shadow-black/25"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-flex min-h-[2.5rem] items-center justify-center rounded-xl border border-zinc-900 bg-zinc-900 px-3.5 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-black/10 transition duration-200 ease-out hover:-translate-y-0.5 hover:border-zinc-800 hover:bg-zinc-800 hover:shadow-lg hover:shadow-zinc-900/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400/60 active:translate-y-0 dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900 dark:ring-white/20 dark:hover:border-white dark:hover:bg-white dark:hover:shadow-white/15"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <div
                class="relative z-10 flex flex-1 flex-col px-4 pb-14 pt-4 sm:px-6 sm:pb-20 sm:pt-6 lg:px-10 lg:pb-24 lg:pt-4"
            >
                <div
                    class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-14 lg:gap-16"
                >
                    <div
                        class="grid flex-1 items-center gap-10 lg:grid-cols-2 lg:gap-12 xl:gap-16"
                    >
                        <div
                            class="order-2 flex flex-col justify-center lg:order-1"
                        >
                            <p
                                class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600/90 dark:text-indigo-400/90"
                            >
                                Premium footwear
                            </p>
                            <h1
                                class="text-balance text-3xl font-semibold leading-[1.15] tracking-tight text-zinc-900 dark:text-white sm:text-4xl sm:leading-[1.1] lg:text-5xl"
                            >
                                Find your next signature pair
                            </h1>
                            <p
                                class="mt-4 max-w-xl text-pretty text-base leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-lg"
                            >
                                Fast delivery, trusted checkout, and a calm
                                shopping flow—built for confident everyday
                                style.
                            </p>

                            <div
                                class="mt-8 flex flex-col gap-3 sm:mt-10 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4"
                            >
                                <Link
                                    :href="products()"
                                    class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-2xl border border-indigo-600 bg-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 ring-1 ring-indigo-500/20 transition duration-200 ease-out hover:-translate-y-0.5 hover:border-indigo-500 hover:bg-indigo-500 hover:shadow-xl hover:shadow-indigo-600/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-50 active:translate-y-0 dark:ring-indigo-400/30 dark:focus-visible:ring-offset-zinc-950 sm:w-auto sm:min-w-[10.5rem]"
                                >
                                    Shop Now
                                </Link>
                                <Link
                                    :href="products()"
                                    class="inline-flex min-h-[3rem] w-full items-center justify-center rounded-2xl border border-zinc-200 bg-white/80 px-6 py-3 text-center text-sm font-semibold text-zinc-800 shadow-sm ring-1 ring-black/[0.04] backdrop-blur-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-zinc-300 hover:bg-white hover:shadow-md hover:shadow-zinc-900/8 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-50 active:translate-y-0 dark:border-zinc-700 dark:bg-zinc-900/60 dark:text-zinc-100 dark:ring-white/[0.06] dark:hover:border-zinc-600 dark:hover:bg-zinc-900 dark:hover:shadow-black/25 sm:w-auto sm:min-w-[10.5rem]"
                                >
                                    Explore Products
                                </Link>
                            </div>
                        </div>

                        <div
                            class="order-1 flex justify-center lg:order-2 lg:justify-end"
                        >
                            <div
                                class="relative w-full max-w-md select-none sm:max-w-lg lg:max-w-none"
                            >
                                <div
                                    class="absolute -left-4 -top-4 h-32 w-32 rounded-full bg-indigo-400/20 blur-3xl dark:bg-indigo-500/15 sm:-left-8 sm:h-40 sm:w-40"
                                />
                                <div
                                    class="absolute -bottom-6 -right-2 h-36 w-36 rounded-full bg-violet-400/15 blur-3xl dark:bg-violet-500/10 sm:-right-6"
                                />

                                <div
                                    class="relative overflow-hidden rounded-3xl border border-zinc-200/80 bg-white/90 p-4 shadow-xl shadow-zinc-900/5 ring-1 ring-black/[0.04] backdrop-blur-md dark:border-zinc-700/80 dark:bg-zinc-900/90 dark:shadow-black/40 dark:ring-white/[0.06] sm:p-5"
                                >
                                    <div
                                        class="flex items-start justify-between gap-3 border-b border-zinc-100 pb-4 dark:border-zinc-800"
                                    >
                                        <div>
                                            <p
                                                class="text-xs font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-400"
                                            >
                                                Featured
                                            </p>
                                            <p
                                                class="mt-1 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white"
                                            >
                                                Signature line
                                            </p>
                                        </div>
                                        <span
                                            class="rounded-full border border-emerald-200/80 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:border-emerald-800/60 dark:bg-emerald-950/50 dark:text-emerald-300"
                                        >
                                            In stock
                                        </span>
                                    </div>

                                    <div
                                        class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-stretch sm:gap-3"
                                    >
                                        <div
                                            class="relative flex min-h-[min(52vh,22rem)] flex-1 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-b from-zinc-100/90 via-zinc-50 to-indigo-50/80 ring-1 ring-inset ring-black/[0.05] dark:from-zinc-800/90 dark:via-zinc-900 dark:to-indigo-950/50 dark:ring-white/[0.06] sm:min-h-[20rem] lg:min-h-[22rem]"
                                        >
                                            <div
                                                class="pointer-events-none absolute left-1/2 top-[40%] h-[42%] w-[88%] max-w-md -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-400/25 blur-[52px] dark:bg-indigo-500/35"
                                            />
                                            <div
                                                class="pointer-events-none absolute left-1/2 top-[32%] h-[28%] w-[55%] max-w-xs -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-400/20 blur-[36px] dark:bg-violet-500/25"
                                            />
                                            <div
                                                class="pointer-events-none absolute bottom-[10%] left-1/2 h-7 w-[58%] max-w-sm -translate-x-1/2 rounded-full bg-zinc-900/25 blur-2xl dark:bg-black/45"
                                            />
                                            <div
                                                class="pointer-events-none absolute inset-[8%] rounded-2xl ring-1 ring-inset ring-white/30 dark:ring-white/[0.06]"
                                            />

                                            <img
                                                :src="heroShowcaseSrc"
                                                alt="Featured Lazora sneaker on a premium spotlight"
                                                class="relative z-10 mx-auto h-auto w-full max-w-[min(100%,20rem)] object-contain object-center drop-shadow-[0_28px_48px_rgba(0,0,0,0.35)] sm:max-w-[min(100%,22rem)] lg:max-w-[min(100%,24rem)]"
                                                loading="eager"
                                                decoding="async"
                                                width="800"
                                                height="1000"
                                            />
                                        </div>

                                        <div
                                            class="flex shrink-0 flex-col justify-between gap-3 sm:w-44"
                                        >
                                            <div
                                                class="rounded-2xl border border-zinc-100 bg-zinc-50/90 p-4 dark:border-zinc-800 dark:bg-zinc-950/50"
                                            >
                                                <p
                                                    class="text-xs text-zinc-500 dark:text-zinc-400"
                                                >
                                                    Est. delivery
                                                </p>
                                                <p
                                                    class="mt-1 text-sm font-semibold text-zinc-900 dark:text-white"
                                                >
                                                    2–4 business days
                                                </p>
                                            </div>
                                            <div
                                                class="rounded-2xl border border-zinc-100 bg-gradient-to-br from-white to-zinc-50 p-4 shadow-inner dark:border-zinc-800 dark:from-zinc-900 dark:to-zinc-950"
                                            >
                                                <div
                                                    class="flex items-end justify-between gap-2"
                                                >
                                                    <div>
                                                        <p
                                                            class="text-xs text-zinc-500 dark:text-zinc-400"
                                                        >
                                                            From
                                                        </p>
                                                        <p
                                                            class="text-2xl font-semibold tabular-nums tracking-tight text-zinc-900 dark:text-white"
                                                        >
                                                            {{ demoFromPrice }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="flex gap-1.5 opacity-80"
                                                        aria-hidden="true"
                                                    >
                                                        <span
                                                            class="h-6 w-6 rounded-full bg-zinc-800 dark:bg-zinc-600"
                                                        />
                                                        <span
                                                            class="h-6 w-6 rounded-full bg-red-700/90 dark:bg-red-600/90"
                                                        />
                                                        <span
                                                            class="h-6 w-6 rounded-full border border-dashed border-zinc-300 dark:border-zinc-600"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section
                        aria-labelledby="welcome-inspiration-heading"
                        class="border-t border-zinc-200/80 pt-12 dark:border-zinc-800/80 sm:pt-14 lg:pt-16"
                    >
                        <p
                            class="text-xs font-semibold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-400"
                        >
                            New season · Premium selection
                        </p>
                        <h2
                            id="welcome-inspiration-heading"
                            class="mt-4 max-w-3xl text-balance text-2xl font-semibold leading-tight tracking-tight text-zinc-900 dark:text-white sm:text-3xl lg:text-4xl"
                        >
                            Elevate every day with curated essentials
                        </h2>
                        <p
                            class="mt-5 max-w-2xl text-pretty text-base leading-relaxed text-zinc-600 dark:text-zinc-400 sm:text-lg"
                        >
                            Discover thoughtfully chosen pieces, seamless
                            checkout, and delivery you can trust—built for a
                            calm, confident shopping experience that feels as
                            considered as the products we carry.
                        </p>
                    </section>
                </div>
            </div>
        </section>
    </div>
</template>
