<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import admin, { dashboard } from '@/routes/admin';

type UserBrief = { id: number; name: string; email: string };

type OrderRow = {
    id: number;
    order_number: string | null;
    status: string;
    payment_status: string;
    total: string;
    created_at: string;
    user: UserBrief;
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type Paginated = {
    data: OrderRow[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
};

defineProps<{
    orders: Paginated;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard.url() },
            { title: 'Orders', href: admin.orders.index.url() },
        ],
    },
});

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

function statusVariant(
    status: string,
): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (status === 'cancelled') {
        return 'destructive';
    }

    if (status === 'placed') {
        return 'default';
    }

    return 'secondary';
}
</script>

<template>
    <Head title="Orders" />

    <div class="flex flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Customer orders</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Review, confirm, or deny orders placed by customers. Denied orders restore stock and notify the customer
                with your reason.
            </p>
        </div>

        <div
            v-if="orders.data.length === 0"
            class="rounded-2xl border border-dashed border-border/80 py-16 text-center text-sm text-muted-foreground"
        >
            No orders yet. When customers check out, they will appear here.
        </div>

        <div v-else class="overflow-hidden rounded-2xl border border-border/70 bg-card shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="border-b border-border/70 bg-muted/30">
                        <tr>
                            <th class="px-4 py-3 font-medium">Order</th>
                            <th class="px-4 py-3 font-medium">Customer</th>
                            <th class="px-4 py-3 font-medium">Total</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="row in orders.data"
                            :key="row.id"
                            class="border-b border-border/50 transition hover:bg-muted/20"
                        >
                            <td class="px-4 py-3 font-mono text-xs font-medium">
                                {{ row.order_number ?? `#${row.id}` }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ row.user.name }}</p>
                                <p class="text-xs text-muted-foreground">{{ row.user.email }}</p>
                            </td>
                            <td class="px-4 py-3 tabular-nums">{{ formatPhp(row.total) }}</td>
                            <td class="px-4 py-3">
                                <Badge :variant="statusVariant(row.status)" class="capitalize">
                                    {{ statusLabel(row.status) }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ new Date(row.created_at).toLocaleString() }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Button variant="outline" size="sm" class="rounded-lg" as-child>
                                    <Link :href="admin.orders.show.url(row.id)">
                                        <Eye class="mr-1 size-4" />
                                        View
                                    </Link>
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <nav v-if="orders.last_page > 1" class="flex flex-wrap justify-center gap-1">
            <template v-for="(link, i) in orders.links" :key="i">
                <Button
                    v-if="link.url"
                    variant="outline"
                    size="sm"
                    class="rounded-lg"
                    :class="{ 'border-primary/40 bg-primary/5': link.active }"
                    as-child
                >
                    <Link :href="link.url" preserve-scroll><span v-html="link.label"></span></Link>
                </Button>
                <span
                    v-else
                    class="flex items-center px-2 py-1 text-xs text-muted-foreground sm:px-3 sm:text-sm"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
