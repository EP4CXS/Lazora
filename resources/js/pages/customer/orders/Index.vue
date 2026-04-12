<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ChevronRight, MapPin, Minus, Plus, ShoppingBag, ShoppingCart, Trash2 } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { getOrderTracking } from '@/composables/useOrderTracking';
import CustomerLayout from '@/layouts/app/CustomerLayout.vue';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';

type Product = {
    name: string;
    slug: string;
    image_url?: string | null;
    price?: string;
    stock?: number;
};
type OrderLine = { quantity: number; line_total: string; product: Product };

type OrderRow = {
    id: number;
    order_number: string | null;
    status: string;
    payment_status: string;
    total: string;
    created_at: string;
    items: OrderLine[];
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type PaginatedOrders = {
    data: OrderRow[];
    links: PaginationLink[];
    last_page: number;
};

type CartRow = {
    id: number;
    quantity: number;
    size?: string | null;
    product: {
        id: number;
        name: string;
        slug: string;
        image_url: string | null;
        price: string;
        stock: number;
    };
};

type CartPayload = {
    items: CartRow[];
    subtotal: number;
    count: number;
};

const props = defineProps<{
    tab: string;
    orders: PaginatedOrders;
    cart: CartPayload;
}>();

defineOptions({
    layout: CustomerLayout,
});

function tabHref(t: string) {
    return customer.orders.index.url({ query: { tab: t } });
}

function tabClass(t: string): string {
    const base =
        'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring';

    if (props.tab === t) {
        return `${base} bg-primary text-primary-foreground shadow-sm`;
    }

    return `${base} text-muted-foreground hover:bg-muted/80 hover:text-foreground`;
}

function statusVariant(status: string): 'default' | 'secondary' | 'outline' | 'destructive' {
    if (status === 'cancelled') {
        return 'destructive';
    }

    if (status === 'delivered') {
        return 'secondary';
    }

    return 'outline';
}

function paymentLabel(s: string): string {
    return s.replace(/_/g, ' ');
}

function tr(status: string) {
    return getOrderTracking(status);
}

function currentStepLabel(status: string): string {
    const t = getOrderTracking(status);

    if (t.isCancelled) {
        return 'Cancelled';
    }

    const step = t.steps[t.currentIndex];

    return step?.label ?? 'Processing';
}
</script>

<template>
    <Head title="My orders" />

    <div class="flex w-full flex-col gap-6 p-4 pb-12">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">My orders</h1>
            <p class="mt-1 max-w-2xl text-sm text-muted-foreground">
                View your order history, track shipment progress, and manage your cart in one place.
            </p>
        </div>

        <div class="flex flex-wrap gap-2 border-b border-border/60 pb-3">
            <Link :href="tabHref('orders')" :class="tabClass('orders')">
                <ShoppingCart class="size-4 shrink-0" aria-hidden="true" />
                <span>Orders</span>
            </Link>
            <Link :href="tabHref('tracking')" :class="tabClass('tracking')">
                <MapPin class="size-4 shrink-0" aria-hidden="true" />
                <span>Tracking</span>
            </Link>
            <Link :href="tabHref('cart')" :class="tabClass('cart')">
                <ShoppingBag class="size-4 shrink-0" aria-hidden="true" />
                <span>Cart</span>
                <span
                    v-if="cart.count > 0"
                    class="ml-1.5 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[0.65rem] font-bold leading-none text-white shadow-sm tabular-nums ring-2 ring-background dark:bg-red-600"
                >
                    {{ cart.count > 99 ? '99+' : cart.count }}
                </span>
            </Link>
        </div>

        <!-- Orders tab -->
        <div v-show="tab === 'orders'" class="flex flex-col gap-6">
            <div
                v-if="orders.data.length === 0"
                class="rounded-2xl border border-dashed border-border/80 py-14 text-left text-sm text-muted-foreground"
            >
                <p class="px-4">You have not placed any orders yet.</p>
                <div class="mt-4 px-4">
                    <Button as-child class="rounded-xl">
                        <Link :href="customer.products.url()">Shop products</Link>
                    </Button>
                </div>
            </div>

            <ul v-else class="flex flex-col gap-4">
                <li v-for="o in orders.data" :key="o.id">
                    <Link
                        :href="customer.orders.show.url(o.id)"
                        class="block rounded-2xl border border-border/70 bg-card/50 p-4 text-left shadow-sm backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-primary/35 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring sm:p-5"
                    >
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-mono text-sm font-semibold">{{ o.order_number ?? `#${o.id}` }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ new Date(o.created_at).toLocaleString() }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <Badge :variant="statusVariant(o.status)" class="rounded-lg capitalize">
                                        {{ o.status }}
                                    </Badge>
                                    <Badge variant="secondary" class="rounded-lg capitalize">
                                        Payment: {{ paymentLabel(o.payment_status) }}
                                    </Badge>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs font-semibold uppercase text-muted-foreground">Total</p>
                                <p class="text-xl font-semibold tabular-nums">{{ formatPhp(o.total) }}</p>
                                <p class="mt-2 flex items-center gap-1 text-xs font-medium text-muted-foreground sm:justify-end">
                                    View order
                                    <ChevronRight class="size-4 shrink-0 text-muted-foreground" aria-hidden="true" />
                                </p>
                            </div>
                        </div>

                        <ul class="mt-4 space-y-3 border-t border-border/60 pt-4 text-sm">
                            <li
                                v-for="(line, i) in o.items"
                                :key="i"
                                class="flex items-start justify-between gap-3"
                            >
                                <div class="flex min-w-0 flex-1 items-center gap-3">
                                    <div
                                        class="size-14 shrink-0 overflow-hidden rounded-lg border border-border/60 bg-muted/30"
                                    >
                                        <img
                                            v-if="line.product.image_url"
                                            :src="line.product.image_url"
                                            :alt="line.product.name"
                                            class="size-full object-cover"
                                        />
                                    </div>
                                    <span class="text-muted-foreground">
                                        {{ line.quantity }} × {{ line.product.name }}
                                    </span>
                                </div>
                                <span class="shrink-0 tabular-nums font-medium text-foreground">{{
                                    formatPhp(line.line_total)
                                }}</span>
                            </li>
                        </ul>
                    </Link>
                </li>
            </ul>

            <nav v-if="tab === 'orders' && orders.last_page > 1" class="flex flex-wrap justify-start gap-1">
                <template v-for="(link, i) in orders.links" :key="i">
                    <Button
                        v-if="link.url"
                        variant="outline"
                        size="sm"
                        class="rounded-lg"
                        :class="{ 'border-primary/40 bg-primary/5': link.active }"
                        as-child
                    >
                        <Link :href="link.url" preserve-scroll
                            ><span v-html="link.label"></span
                        ></Link>
                    </Button>
                    <span
                        v-else
                        class="flex items-center px-3 py-1 text-sm text-muted-foreground"
                        v-html="link.label"
                    />
                </template>
            </nav>
        </div>

        <!-- Tracking tab -->
        <div v-show="tab === 'tracking'" class="flex flex-col gap-6">
            <p class="text-sm text-muted-foreground">Follow fulfillment progress for your purchases.</p>

            <div
                v-if="orders.data.length === 0"
                class="rounded-2xl border border-dashed border-border/80 py-14 text-left text-sm text-muted-foreground"
            >
                <p class="px-4">No orders to track yet.</p>
                <div class="mt-4 px-4">
                    <Button as-child class="rounded-xl">
                        <Link :href="customer.products.url()">Browse products</Link>
                    </Button>
                </div>
            </div>

            <ul v-else class="flex flex-col gap-4">
                <li v-for="o in orders.data" :key="o.id">
                    <Link
                        :href="customer.orders.show.url(o.id)"
                        class="block rounded-2xl border border-border/70 bg-card/50 p-4 text-left shadow-sm backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-primary/35 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring sm:p-5"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-mono text-sm font-semibold">{{ o.order_number ?? `#${o.id}` }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ new Date(o.created_at).toLocaleString() }}</p>
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <Badge variant="outline" class="rounded-lg capitalize">{{ o.status }}</Badge>
                                    <span class="text-xs text-muted-foreground">Now: {{ currentStepLabel(o.status) }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-stretch gap-2 sm:items-end">
                                <p class="text-lg font-semibold tabular-nums sm:text-right">{{ formatPhp(o.total) }}</p>
                                <p class="flex items-center gap-1 text-xs font-medium text-muted-foreground sm:justify-end">
                                    View timeline
                                    <ChevronRight class="size-4 shrink-0" aria-hidden="true" />
                                </p>
                            </div>
                        </div>

                    <div v-if="!tr(o.status).isCancelled" class="mt-4 border-t border-border/60 pt-4">
                        <div class="flex justify-between gap-1">
                            <div v-for="(step, idx) in tr(o.status).steps" :key="step.key" class="flex-1">
                                <div
                                    class="h-1.5 rounded-full transition"
                                    :class="
                                        idx <= tr(o.status).currentIndex
                                            ? 'bg-primary shadow-sm shadow-primary/30'
                                            : 'bg-muted'
                                    "
                                />
                                <p class="mt-1.5 hidden text-[10px] text-muted-foreground sm:block sm:truncate">
                                    {{ step.label }}
                                </p>
                            </div>
                        </div>
                    </div>
                    </Link>
                </li>
            </ul>

            <nav v-if="tab === 'tracking' && orders.last_page > 1" class="flex flex-wrap justify-start gap-1">
                <template v-for="(link, i) in orders.links" :key="i">
                    <Button
                        v-if="link.url"
                        variant="outline"
                        size="sm"
                        class="rounded-lg"
                        :class="{ 'border-primary/40 bg-primary/5': link.active }"
                        as-child
                    >
                        <Link :href="link.url" preserve-scroll
                            ><span v-html="link.label"></span
                        ></Link>
                    </Button>
                    <span
                        v-else
                        class="flex items-center px-3 py-1 text-sm text-muted-foreground"
                        v-html="link.label"
                    />
                </template>
            </nav>
        </div>

        <!-- Cart tab -->
        <div v-show="tab === 'cart'" class="flex flex-col gap-6">
            <p class="text-sm text-muted-foreground">
                {{ cart.count }} item<span v-if="cart.count !== 1">s</span> — adjust quantities or checkout when ready.
            </p>

            <div
                v-if="cart.items.length === 0"
                class="rounded-2xl border border-dashed border-border/80 py-14 text-left text-sm text-muted-foreground"
            >
                <p class="px-4">Your cart is empty.</p>
                <div class="mt-4 px-4">
                    <Button as-child class="rounded-xl">
                        <Link :href="customer.products.url()">Browse products</Link>
                    </Button>
                </div>
            </div>

            <ul v-else class="flex flex-col gap-3">
                <li
                    v-for="row in cart.items"
                    :key="row.id"
                    class="flex flex-col gap-4 rounded-2xl border border-border/70 bg-card/50 p-4 text-left shadow-sm backdrop-blur-sm sm:flex-row sm:items-center"
                >
                    <div class="flex gap-4">
                        <div
                            class="size-20 shrink-0 overflow-hidden rounded-xl border border-border/60 bg-muted/30 sm:size-24"
                        >
                            <img
                                v-if="row.product.image_url"
                                :src="row.product.image_url"
                                :alt="row.product.name"
                                class="size-full object-cover"
                            />
                        </div>
                        <div class="min-w-0">
                            <Link
                                :href="customer.products.show.url(row.product.slug)"
                                class="font-medium transition hover:text-primary"
                            >
                                {{ row.product.name }}
                            </Link>
                            <p class="mt-1 text-sm tabular-nums text-muted-foreground">
                                {{ formatPhp(row.product.price) }} each
                            </p>
                            <p v-if="row.size" class="mt-0.5 text-xs text-muted-foreground">Size EU {{ row.size }}</p>
                        </div>
                    </div>

                    <div class="flex flex-1 flex-wrap items-center justify-start gap-3 sm:justify-end">
                        <Form v-bind="customer.cart.update.form(row.id)" #default="{ processing }" class="flex items-center gap-2">
                            <input type="hidden" name="quantity" :value="Math.max(0, row.quantity - 1)" />
                            <Button
                                type="submit"
                                variant="outline"
                                size="icon"
                                class="size-9 shrink-0 rounded-xl"
                                :disabled="processing || row.quantity <= 1"
                                aria-label="Decrease quantity"
                            >
                                <Minus class="size-4" />
                            </Button>
                        </Form>

                        <Form v-bind="customer.cart.update.form(row.id)" class="flex items-center">
                            <Input
                                :default-value="String(row.quantity)"
                                name="quantity"
                                type="number"
                                min="1"
                                :max="row.product.stock"
                                class="h-9 w-16 rounded-xl text-center"
                                @change="($event.target as HTMLInputElement).form?.requestSubmit()"
                            />
                        </Form>

                        <Form v-bind="customer.cart.update.form(row.id)" #default="{ processing }" class="flex items-center">
                            <input type="hidden" name="quantity" :value="Math.min(row.product.stock, row.quantity + 1)" />
                            <Button
                                type="submit"
                                variant="outline"
                                size="icon"
                                class="size-9 shrink-0 rounded-xl"
                                :disabled="processing || row.quantity >= row.product.stock"
                                aria-label="Increase quantity"
                            >
                                <Plus class="size-4" />
                            </Button>
                        </Form>

                        <p class="min-w-[5rem] text-left text-sm font-semibold tabular-nums sm:text-right sm:text-base">
                            {{ formatPhp(Number(row.product.price) * row.quantity) }}
                        </p>

                        <Form v-bind="customer.cart.destroy.form(row.id)" #default="{ processing }">
                            <Button
                                type="submit"
                                variant="ghost"
                                size="icon"
                                class="size-9 shrink-0 text-destructive hover:text-destructive"
                                :disabled="processing"
                                aria-label="Remove"
                            >
                                <Trash2 class="size-4" />
                            </Button>
                        </Form>
                    </div>
                </li>
            </ul>

            <div
                v-if="cart.items.length"
                class="flex flex-col gap-4 rounded-2xl border border-border/70 bg-card/60 p-5 text-left shadow-sm sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <p class="text-xs font-semibold uppercase text-muted-foreground">Subtotal</p>
                    <p class="text-2xl font-semibold tabular-nums">{{ formatPhp(cart.subtotal) }}</p>
                    <p class="mt-1 text-xs text-muted-foreground">Taxes and shipping shown at checkout (demo).</p>
                </div>
                <Form v-bind="customer.orders.store.form()" #default="{ processing }">
                    <input type="hidden" name="from_cart" value="1" />
                    <Button type="submit" size="lg" class="w-full rounded-xl sm:w-auto" :disabled="processing">
                        Proceed to checkout
                    </Button>
                </Form>
            </div>
        </div>
    </div>
</template>
