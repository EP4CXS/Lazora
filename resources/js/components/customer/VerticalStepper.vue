<script setup lang="ts">
import { Check } from 'lucide-vue-next';

export type StepperStep = { key: string; label: string };

withDefaults(
    defineProps<{
        title?: string;
        steps: StepperStep[];
        /** Index of the highlighted “current” step (0-based). Use -1 for none. */
        currentIndex: number;
        /** When true, show a cancelled message instead of the list. */
        cancelled?: boolean;
        /** When true, completed steps (before current) show a checkmark. */
        showCompletedChecks?: boolean;
        /** Label under the current step (e.g. “Start here” on product pages). */
        currentStageLabel?: string;
    }>(),
    {
        title: '',
        cancelled: false,
        showCompletedChecks: true,
        currentStageLabel: 'Current stage',
    },
);
</script>

<template>
    <section class="rounded-2xl border border-border/70 bg-card/50 p-5 shadow-sm">
        <h2 v-if="title" class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">
            {{ title }}
        </h2>

        <p v-if="cancelled" class="mt-4 text-sm text-destructive">This order was cancelled.</p>

        <ol v-else class="mt-6 space-y-0">
            <li
                v-for="(step, index) in steps"
                :key="step.key"
                class="relative flex gap-4 pb-8 last:pb-0"
            >
                <div
                    class="absolute left-[15px] top-8 h-[calc(100%-8px)] w-px bg-border/80 last:hidden"
                    aria-hidden="true"
                />
                <div
                    class="relative z-[1] flex size-8 shrink-0 items-center justify-center rounded-full border-2 text-xs font-semibold transition"
                    :class="
                        showCompletedChecks && index < currentIndex
                            ? 'border-white bg-white text-black shadow-sm dark:border-white dark:bg-white dark:text-black'
                            : index === currentIndex
                              ? 'border-white bg-white text-black shadow-md ring-2 ring-white/10 dark:border-white dark:bg-white dark:text-black'
                              : 'border-muted-foreground/35 bg-background/40 text-muted-foreground'
                    "
                >
                    <Check
                        v-if="showCompletedChecks && index < currentIndex"
                        class="size-4 text-black dark:text-black"
                    />
                    <span v-else>{{ index + 1 }}</span>
                </div>
                <div class="min-w-0 pt-0.5">
                    <p
                        class="leading-none"
                        :class="
                            index === currentIndex
                                ? 'font-semibold text-foreground'
                                : index < currentIndex
                                  ? 'font-medium text-foreground'
                                  : 'font-medium text-muted-foreground'
                        "
                    >
                        {{ step.label }}
                    </p>
                    <p
                        v-if="index === currentIndex && currentStageLabel"
                        class="mt-1 text-xs text-muted-foreground"
                    >
                        {{ currentStageLabel }}
                    </p>
                </div>
            </li>
        </ol>
    </section>
</template>
