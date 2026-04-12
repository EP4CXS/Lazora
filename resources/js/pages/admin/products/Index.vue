<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Eye, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import { dashboard } from '@/routes/admin';

type ProductRow = {
    id: number;
    name: string;
    slug: string;
    category: string;
    price: string;
    stock: number;
    is_featured: boolean;
    is_active: boolean;
    image_url: string | null;
};

type PaginationLink = { url: string | null; label: string; active: boolean };

type PaginatedProducts = {
    data: ProductRow[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
};

defineProps<{
    products: PaginatedProducts;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard() },
            { title: 'Products', href: ProductController.index.url() },
        ],
    },
});

</script>

<template>
    <Head title="Products" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Products</h1>
                <p class="mt-1 text-sm text-muted-foreground">Create, edit, and manage catalog items.</p>
            </div>
            <Button as-child class="rounded-xl shadow-sm transition hover:shadow-md">
                <Link :href="ProductController.create.url()">
                    <Plus class="mr-2 size-4" />
                    New product
                </Link>
            </Button>
        </div>

        <!-- Mobile cards -->
        <div class="flex flex-col gap-3 md:hidden">
            <div
                v-for="p in products.data"
                :key="p.id"
                class="rounded-2xl border border-border/80 bg-card/50 p-4 shadow-sm backdrop-blur-sm transition hover:border-primary/20 hover:shadow-md"
            >
                <div class="flex gap-3">
                    <div
                        class="size-14 shrink-0 overflow-hidden rounded-xl border border-border/60 bg-muted/30"
                    >
                        <img
                            v-if="p.image_url"
                            :src="p.image_url"
                            :alt="p.name"
                            class="size-full object-cover"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ p.name }}</p>
                        <p class="text-xs text-muted-foreground">{{ p.category }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <Badge v-if="p.is_featured" variant="secondary" class="text-xs">Featured</Badge>
                            <Badge :variant="p.is_active ? 'default' : 'outline'" class="text-xs">
                                {{ p.is_active ? 'Active' : 'Hidden' }}
                            </Badge>
                        </div>
                        <p class="mt-2 text-sm font-semibold">{{ formatPhp(p.price) }} · {{ p.stock }} in stock</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <Button variant="outline" size="sm" class="rounded-lg" as-child>
                        <Link :href="ProductController.show.url(p.id)">
                            <Eye class="mr-1 size-3.5" />
                            View
                        </Link>
                    </Button>
                    <Button variant="outline" size="sm" class="rounded-lg" as-child>
                        <Link :href="ProductController.edit.url(p.id)">
                            <Pencil class="mr-1 size-3.5" />
                            Edit
                        </Link>
                    </Button>
                    <Form v-bind="ProductController.destroy.form(p.id)" #default="{ processing }" class="inline">
                        <Button type="submit" variant="destructive" size="sm" class="rounded-lg" :disabled="processing">
                            <Trash2 class="mr-1 size-3.5" />
                            Delete
                        </Button>
                    </Form>
                </div>
            </div>
        </div>

        <!-- Desktop table -->
        <div
            class="hidden overflow-x-auto rounded-2xl border border-border/80 bg-card/40 shadow-sm backdrop-blur-sm md:block"
        >
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-border/80 bg-muted/30 text-xs uppercase text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Price</th>
                        <th class="px-4 py-3 font-medium">Stock</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="p in products.data"
                        :key="p.id"
                        class="border-b border-border/60 transition hover:bg-muted/20"
                    >
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-10 overflow-hidden rounded-lg border border-border/60 bg-muted/20"
                                >
                                    <img
                                        v-if="p.image_url"
                                        :src="p.image_url"
                                        :alt="p.name"
                                        class="size-full object-cover"
                                    />
                                </div>
                                <span class="font-medium">{{ p.name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ p.category }}</td>
                        <td class="px-4 py-3">{{ formatPhp(p.price) }}</td>
                        <td class="px-4 py-3">{{ p.stock }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <Badge v-if="p.is_featured" variant="secondary" class="text-xs">Featured</Badge>
                                <Badge :variant="p.is_active ? 'default' : 'outline'" class="text-xs">
                                    {{ p.is_active ? 'Active' : 'Hidden' }}
                                </Badge>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <Button variant="ghost" size="icon" class="rounded-lg" as-child>
                                    <Link :href="ProductController.show.url(p.id)">
                                        <Eye class="size-4" />
                                    </Link>
                                </Button>
                                <Button variant="ghost" size="icon" class="rounded-lg" as-child>
                                    <Link :href="ProductController.edit.url(p.id)">
                                        <Pencil class="size-4" />
                                    </Link>
                                </Button>
                                <Form v-bind="ProductController.destroy.form(p.id)" #default="{ processing }" class="inline">
                                    <Button
                                        type="submit"
                                        variant="ghost"
                                        size="icon"
                                        class="rounded-lg text-destructive hover:text-destructive"
                                        :disabled="processing"
                                    >
                                        <Trash2 class="size-4" />
                                    </Button>
                                </Form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="products.data.length === 0" class="rounded-2xl border border-dashed border-border/80 p-10 text-center">
            <p class="text-sm text-muted-foreground">No products yet.</p>
            <Button as-child class="mt-4 rounded-xl">
                <Link :href="ProductController.create.url()">Create your first product</Link>
            </Button>
        </div>

        <nav v-if="products.last_page > 1" class="flex flex-wrap justify-center gap-1">
            <template v-for="(link, i) in products.links" :key="i">
                <Button
                    v-if="link.url"
                    variant="outline"
                    size="sm"
                    class="rounded-lg"
                    :class="{ 'border-primary/40 bg-primary/5': link.active }"
                    as-child
                >
                    <Link :href="link.url" preserve-scroll v-html="link.label" />
                </Button>
                <span
                    v-else
                    class="flex items-center px-3 py-1 text-sm text-muted-foreground"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
