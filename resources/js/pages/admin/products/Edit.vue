<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes/admin';
import { Form, Head, Link } from '@inertiajs/vue3';

type Product = {
    id: number;
    name: string;
    slug: string;
    category: string;
    color: string | null;
    sizes: string | null;
    description: string | null;
    price: string;
    stock: number;
    is_featured: boolean;
    is_active: boolean;
    image_url: string | null;
};

defineProps<{
    product: Product;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard() },
            { title: 'Products', href: ProductController.index.url() },
            { title: 'Edit', href: ProductController.index.url() },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit · ${product.name}`" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4 pb-10">
        <Heading variant="small" title="Edit product" :description="product.name" />

        <Form
            v-bind="ProductController.update.form(product.id)"
            #default="{ errors, processing }"
            class="space-y-6"
            enctype="multipart/form-data"
        >
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input id="name" name="name" required class="rounded-xl" :default-value="product.name" />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug</Label>
                <Input id="slug" name="slug" class="rounded-xl" :default-value="product.slug" />
                <InputError :message="errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Input id="category" name="category" required class="rounded-xl" :default-value="product.category" />
                <InputError :message="errors.category" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="color">Color (optional)</Label>
                    <Input id="color" name="color" class="rounded-xl" :default-value="product.color ?? ''" />
                    <InputError :message="errors.color" />
                </div>
                <div class="grid gap-2">
                    <Label for="sizes">Sizes (optional)</Label>
                    <Input id="sizes" name="sizes" class="rounded-xl" :default-value="product.sizes ?? ''" />
                    <InputError :message="errors.sizes" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="min-h-[100px] w-full rounded-xl border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none transition focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                >
                    {{ product.description ?? '' }}
                </textarea>
                <InputError :message="errors.description" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="price">Price (PHP)</Label>
                    <Input
                        id="price"
                        name="price"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        class="rounded-xl"
                        :default-value="product.price"
                    />
                    <InputError :message="errors.price" />
                </div>
                <div class="grid gap-2">
                    <Label for="stock">Stock</Label>
                    <Input
                        id="stock"
                        name="stock"
                        type="number"
                        min="0"
                        required
                        class="rounded-xl"
                        :default-value="String(product.stock)"
                    />
                    <InputError :message="errors.stock" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="rating">Rating (0–5)</Label>
                <Input
                    id="rating"
                    name="rating"
                    type="number"
                    step="0.1"
                    min="0"
                    max="5"
                    required
                    class="rounded-xl"
                    :default-value="String(product.rating)"
                />
                <InputError :message="errors.rating" />
            </div>

            <div class="grid gap-2">
                <Label for="image_url">Image URL</Label>
                <Input
                    id="image_url"
                    name="image_url"
                    type="text"
                    class="rounded-xl"
                    :default-value="product.image_url ?? ''"
                />
                <InputError :message="errors.image_url" />
            </div>

            <div class="grid gap-2">
                <Label for="image">Replace image (optional)</Label>
                <Input id="image" name="image" type="file" accept="image/*" class="cursor-pointer rounded-xl text-sm" />
                <InputError :message="errors.image" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="is_featured">Featured</Label>
                    <select
                        id="is_featured"
                        name="is_featured"
                        class="h-9 w-full rounded-xl border border-input bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    >
                        <option value="0" :selected="!product.is_featured">No</option>
                        <option value="1" :selected="product.is_featured">Yes</option>
                    </select>
                    <InputError :message="errors.is_featured" />
                </div>
                <div class="grid gap-2">
                    <Label for="is_active">Visibility</Label>
                    <select
                        id="is_active"
                        name="is_active"
                        class="h-9 w-full rounded-xl border border-input bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    >
                        <option value="1" :selected="product.is_active">Active</option>
                        <option value="0" :selected="!product.is_active">Hidden</option>
                    </select>
                    <InputError :message="errors.is_active" />
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <Button variant="outline" class="rounded-xl" as-child>
                    <Link :href="ProductController.show.url(product.id)">Cancel</Link>
                </Button>
                <Button type="submit" class="rounded-xl" :disabled="processing">
                    {{ processing ? 'Saving…' : 'Save changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>
