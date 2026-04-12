<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ClipboardList,
    Home,
    Package,
    ShieldCheck,
    Truck,
    ArrowLeft,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Stepper,
    StepperDescription,
    StepperIndicator,
    StepperItem,
    StepperSeparator,
    StepperTitle,
    StepperTrigger,
} from '@/components/ui/stepper';
import { getOrderTracking } from '@/composables/useOrderTracking';
import CustomerLayout from '@/layouts/app/CustomerLayout.vue';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';

type Product = { name: string; slug: string; image_url: string | null };
type Line = {
    quantity: number;
    unit_price: string;
    line_total: string;
    product: Product;
};

type OrderDetail = {
    id: number;
    order_number: string | null;
    status: string;
    payment_status: string;
    total: string;
    notes: string | null;
    denial_reason: string | null;
    created_at: string;
    items: Line[];
};

const props = defineProps<{
    order: OrderDetail;
}>();

defineOptions({
    layout: CustomerLayout,
});

const tracking = computed(() => getOrderTracking(props.order.status));

/** 1-based step for shadcn Stepper `v-model` (synced from order status). */
const activeStep = ref(1);

watch(
    () => props.order.status,
    () => {
        const t = getOrderTracking(props.order.status);

        if (!t.isCancelled) {
            activeStep.value = t.currentIndex + 1;
        }
    },
    { immediate: true },
);

const shipmentSteps = [
    {
        step: 1,
        title: 'Order placed',
        description: 'We received your order',
        icon: ClipboardList,
    },
    {
        step: 2,
        title: 'Confirmed',
        description: 'Payment verified',
        icon: ShieldCheck,
    },
    {
        step: 3,
        title: 'Packed',
        description: 'Preparing shipment',
        icon: Package,
    },
    {
        step: 4,
        title: 'Shipped',
        description: 'On the way to you',
        icon: Truck,
    },
    {
        step: 5,
        title: 'Delivered',
        description: 'Arrived at your door',
        icon: Home,
    },
] as const;

</script>

<template>
    <Head :title="`Order ${order.order_number ?? order.id}`" />

    <div class="flex w-full flex-col gap-8 p-4 pb-12">
        <Button variant="ghost" size="sm" class="-ml-2 w-fit rounded-xl text-muted-foreground" as-child>
            <Link :href="customer.orders.index.url()">
                <ArrowLeft class="mr-2 size-4" />
                Back to orders
            </Link>
        </Button>

        <div>
            <p class="font-mono text-lg font-semibold">{{ order.order_number ?? `Order #${order.id}` }}</p>
            <p class="mt-1 text-sm text-muted-foreground">{{ new Date(order.created_at).toLocaleString() }}</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <Badge variant="outline" class="rounded-lg capitalize">{{ order.status }}</Badge>
                <Badge variant="secondary" class="rounded-lg capitalize">{{ order.payment_status }}</Badge>
            </div>
        </div>

        <section
            v-if="!tracking.isCancelled"
            class="rounded-2xl border border-border/70 bg-card/50 p-4 shadow-sm sm:p-6"
        >
            <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">
                Shipment progress
            </h2>
            <p class="mt-1 text-xs text-muted-foreground">
                Tap a step to explore your timeline. The highlighted step matches your order status.
            </p>

            <Stepper
                v-model="activeStep"
                :linear="false"
                class="mt-6 flex w-full min-w-0 items-start gap-2 overflow-x-auto pb-2 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:gap-1"
            >
                <StepperItem
                    v-for="(item, index) in shipmentSteps"
                    :key="item.step"
                    v-slot="{ state }"
                    :step="item.step"
                    class="relative flex min-w-[5.5rem] flex-1 flex-col items-center sm:min-w-0"
                >
                    <StepperSeparator
                        v-if="index < shipmentSteps.length - 1"
                        class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 z-0 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                    />

                    <StepperTrigger class="relative z-10">
                        <StepperIndicator class="size-10 bg-muted">
                            <component :is="item.icon" class="size-4 shrink-0" aria-hidden="true" />
                        </StepperIndicator>
                    </StepperTrigger>

                    <div class="mt-3 flex max-w-[10rem] flex-col items-center px-0.5 text-center">
                        <StepperTitle
                            class="text-xs font-semibold leading-tight sm:text-sm"
                            :class="state === 'active' ? 'text-primary' : ''"
                        >
                            {{ item.title }}
                        </StepperTitle>
                        <StepperDescription
                            class="mt-1 text-[0.65rem] leading-snug sm:text-xs"
                            :class="state === 'active' ? 'text-primary/90' : ''"
                        >
                            {{ item.description }}
                        </StepperDescription>
                    </div>
                </StepperItem>
            </Stepper>
        </section>

        <section v-else class="rounded-2xl border border-destructive/40 bg-destructive/5 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-destructive">Order denied</h2>
            <p class="mt-2 text-sm text-destructive">This order could not be fulfilled.</p>
            <p v-if="order.denial_reason" class="mt-3 rounded-xl border border-destructive/30 bg-background/80 p-3 text-sm leading-relaxed text-foreground">
                <span class="font-medium text-destructive">Reason: </span>{{ order.denial_reason }}
            </p>
        </section>

        <section class="rounded-2xl border border-border/70 bg-card/50 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Items</h2>
            <ul class="mt-4 space-y-4">
                <li
                    v-for="(line, i) in order.items"
                    :key="i"
                    class="flex gap-3 border-b border-border/50 pb-4 last:border-0 last:pb-0"
                >
                    <div class="size-14 shrink-0 overflow-hidden rounded-lg border border-border/60 bg-muted/30">
                        <img
                            v-if="line.product.image_url"
                            :src="line.product.image_url"
                            :alt="line.product.name"
                            class="size-full object-cover"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-medium">{{ line.product.name }}</p>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ line.quantity }} × {{ formatPhp(line.unit_price) }}
                        </p>
                    </div>
                    <p class="shrink-0 text-sm font-semibold tabular-nums">{{ formatPhp(line.line_total) }}</p>
                </li>
            </ul>
            <div class="mt-6 flex items-center justify-between border-t border-border/60 pt-4">
                <span class="text-sm font-semibold">Total</span>
                <span class="text-lg font-semibold tabular-nums">{{ formatPhp(order.total) }}</span>
            </div>
            <p v-if="order.notes" class="mt-4 text-sm text-muted-foreground">
                <span class="font-medium text-foreground">Notes: </span>{{ order.notes }}
            </p>
        </section>
    </div>
</template>
