<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Ban, Check } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { formatPhp } from '@/lib/currency';
import admin, { dashboard } from '@/routes/admin';

type ProductBrief = { id: number; name: string; slug: string; image_url: string | null };
type Line = {
    quantity: number;
    unit_price: string;
    line_total: string;
    product: ProductBrief;
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
    user: { id: number; name: string; email: string };
    items: Line[];
};

const props = defineProps<{
    order: OrderDetail;
}>();

const denyOpen = ref(false);

const denyForm = useForm({
    reason: '',
});

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard.url() },
            { title: 'Orders', href: admin.orders.index.url() },
        ],
    },
});

function confirmOrder(): void {
    router.post(
        admin.orders.confirm.url(props.order.id),
        {},
        {
            preserveScroll: true,
        },
    );
}

function submitDeny(): void {
    denyForm.post(admin.orders.deny.url(props.order.id), {
        preserveScroll: true,
        onSuccess: () => {
            denyOpen.value = false;
            denyForm.reset();
        },
    });
}

function statusLabel(status: string): string {
    const map: Record<string, string> = {
        placed: 'Awaiting confirmation',
        confirmed: 'Confirmed',
        packed: 'Packed',
        shipped: 'Shipped',
        delivered: 'Delivered',
        cancelled: 'Denied',
    };

    return map[status] ?? status;
}

const awaitingAction = computed(() => props.order.status === 'placed');
</script>

<template>
    <Head :title="`Order ${order.order_number ?? order.id}`" />

    <div class="flex flex-col gap-6 p-4 pb-12">
        <Button variant="ghost" size="sm" class="-ml-2 w-fit rounded-xl text-muted-foreground" as-child>
            <Link :href="admin.orders.index.url()">
                <ArrowLeft class="mr-2 size-4" />
                Back to orders
            </Link>
        </Button>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="font-mono text-lg font-semibold">{{ order.order_number ?? `Order #${order.id}` }}</p>
                <p class="mt-1 text-sm text-muted-foreground">{{ new Date(order.created_at).toLocaleString() }}</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <Badge variant="outline" class="rounded-lg capitalize">{{ statusLabel(order.status) }}</Badge>
                    <Badge variant="secondary" class="rounded-lg capitalize">{{ order.payment_status }}</Badge>
                </div>
            </div>

            <div v-if="awaitingAction" class="flex flex-wrap gap-2">
                <Button type="button" class="rounded-xl" @click="confirmOrder">
                    <Check class="mr-2 size-4" />
                    Confirm order
                </Button>
                <Button type="button" variant="destructive" class="rounded-xl" @click="denyOpen = true">
                    <Ban class="mr-2 size-4" />
                    Deny order
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-border/70 bg-card/50 p-5 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Customer</h2>
                <p class="mt-3 font-medium">{{ order.user.name }}</p>
                <p class="mt-1 text-sm text-muted-foreground">{{ order.user.email }}</p>
            </section>

            <section class="rounded-2xl border border-border/70 bg-card/50 p-5 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Totals</h2>
                <p class="mt-3 text-2xl font-semibold tabular-nums">{{ formatPhp(order.total) }}</p>
                <p v-if="order.notes" class="mt-3 text-sm text-muted-foreground">
                    <span class="font-medium text-foreground">Notes:</span> {{ order.notes }}
                </p>
                <p v-if="order.denial_reason" class="mt-3 text-sm text-destructive">
                    <span class="font-medium">Denial reason on file:</span> {{ order.denial_reason }}
                </p>
            </section>
        </div>

        <section class="rounded-2xl border border-border/70 bg-card/50 p-5 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Line items</h2>
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
        </section>

        <Dialog v-model:open="denyOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Deny this order?</DialogTitle>
                    <DialogDescription>
                        Stock will be returned to inventory. The customer will see your reason on their order and
                        tracking views.
                    </DialogDescription>
                </DialogHeader>
                <form class="space-y-4" @submit.prevent="submitDeny">
                    <div class="space-y-2">
                        <label for="deny-reason" class="text-sm font-medium">Reason</label>
                        <textarea
                            id="deny-reason"
                            v-model="denyForm.reason"
                            required
                            rows="4"
                            class="flex min-h-[100px] w-full rounded-xl border border-input bg-background px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="e.g. Requested size is no longer available."
                        />
                        <p v-if="denyForm.errors.reason" class="text-sm text-destructive">{{ denyForm.errors.reason }}</p>
                    </div>
                    <DialogFooter class="gap-2 sm:gap-0">
                        <Button type="button" variant="outline" class="rounded-xl" @click="denyOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit" variant="destructive" class="rounded-xl" :disabled="denyForm.processing">
                            Deny order
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>
