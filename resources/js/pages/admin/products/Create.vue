<script setup lang="ts">
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard } from '@/routes/admin';
import { Form, Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin Dashboard', href: dashboard() },
            { title: 'Products', href: ProductController.index.url() },
            { title: 'Create', href: ProductController.create.url() },
        ],
    },
});
</script>

<template>
    <Head title="Create product" />

    <div class="mx-auto flex max-w-2xl flex-col gap-8 p-4 pb-10">
        <Heading
            variant="small"
            title="New product"
            description="Add an item to the storefront catalog."
        />

        <Form
            v-bind="ProductController.store.form()"
            #default="{ errors, processing }"
            class="space-y-6"
            enctype="multipart/form-data"
        >
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input id="name" name="name" required class="rounded-xl" placeholder="Product name" />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="slug">Slug (optional)</Label>
                <Input id="slug" name="slug" class="rounded-xl" placeholder="auto-generated from name if empty" />
                <InputError :message="errors.slug" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Input id="category" name="category" required class="rounded-xl" placeholder="e.g. Running Shoes" />
                <InputError :message="errors.category" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="color">Color (optional)</Label>
                    <Input id="color" name="color" class="rounded-xl" placeholder="e.g. Black / White" />
                    <InputError :message="errors.color" />
                </div>
                <div class="grid gap-2">
                    <Label for="sizes">Sizes (optional)</Label>
                    <Input id="sizes" name="sizes" class="rounded-xl" placeholder="e.g. US Men's 7 – 13" />
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
                    placeholder="Short description for customers"
                />
                <InputError :message="errors.description" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="price">Price (PHP)</Label>
                    <Input id="price" name="price" type="number" step="0.01" min="0" required class="rounded-xl" />
                    <InputError :message="errors.price" />
                </div>
                <div class="grid gap-2">
                    <Label for="stock">Stock</Label>
                    <Input id="stock" name="stock" type="number" min="0" required class="rounded-xl" />
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
                    :default-value="'4.5'"
                />
                <InputError :message="errors.rating" />
            </div>

            <div class="grid gap-2">
                <Label for="image_url">Image URL (optional)</Label>
                <Input
                    id="image_url"
                    name="image_url"
                    type="text"
                    class="rounded-xl"
                    placeholder="https://… or /images/products/…"
                />
                <InputError :message="errors.image_url" />
            </div>

            <div class="grid gap-2">
                <Label for="image">Upload image (optional)</Label>
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
                        <option value="0" selected>No</option>
                        <option value="1">Yes</option>
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
                        <option value="1" selected>Active (visible to customers)</option>
                        <option value="0">Hidden</option>
                    </select>
                    <InputError :message="errors.is_active" />
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <Button variant="outline" class="rounded-xl" as-child>
                    <Link :href="ProductController.index.url()">Cancel</Link>
                </Button>
                <Button type="submit" class="rounded-xl" :disabled="processing">
                    {{ processing ? 'Saving…' : 'Create product' }}
                </Button>
            </div>
        </Form>
    </div>
</template>
