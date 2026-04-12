<script setup lang="ts">
import { ShoppingBag } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { formatPhp } from '@/lib/currency';
import type { CustomerProductListItem } from '@/types/customerProductList';

defineProps<{
    product: CustomerProductListItem;
}>();

const emit = defineEmits<{
    details: [];
    'quick-add': [];
}>();
</script>

<template>
    <article
        class="flex min-h-0 flex-col overflow-hidden rounded-2xl border border-border/60 bg-card/80 shadow-sm ring-1 ring-black/[0.04] dark:bg-card/50 dark:ring-white/[0.06]"
    >
        <div
            class="relative flex aspect-[4/3] items-center justify-center overflow-hidden bg-gradient-to-b from-muted/30 to-muted/10"
        >
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_70%_at_50%_55%,rgba(99,102,241,0.1),transparent_70%)]"
            />
            <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.name"
                class="relative z-[1] max-h-full max-w-full object-contain px-2 py-3"
            />
            <div v-else class="relative z-[1] text-[10px] text-muted-foreground">No image</div>
        </div>

        <div class="flex min-h-0 flex-1 flex-col gap-1.5 p-2.5">
            <p class="line-clamp-2 min-h-[2.25rem] text-left text-[11px] font-semibold leading-tight tracking-tight text-foreground">
                {{ product.name }}
            </p>
            <p class="text-left text-sm font-semibold tabular-nums text-foreground">{{ formatPhp(product.price) }}</p>

            <div class="mt-auto flex flex-col gap-1.5 pt-0.5">
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="h-8 w-full rounded-lg border-border/80 px-2 text-[11px] font-medium"
                    @click="emit('details')"
                >
                    Details
                </Button>

                <Button
                    type="button"
                    size="sm"
                    class="h-8 w-full rounded-lg px-2 text-[11px] font-semibold"
                    :disabled="product.stock < 1"
                    @click="emit('quick-add')"
                >
                    <ShoppingBag class="mr-1 size-3.5 shrink-0" />
                    Add to cart
                </Button>
            </div>
        </div>
    </article>
</template>
