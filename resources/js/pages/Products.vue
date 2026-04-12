<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        title: 'Products',
        description: 'Browse our products',
    },
});
</script>

<template>
    <Head title="Products" />

    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold">Products</h1>
            <p class="text-muted-foreground">Browse our products</p>
        </div>
    </div>
</template>
