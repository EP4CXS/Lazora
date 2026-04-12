<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import { ExternalLink, ShoppingBag, Zap } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import StarRating from '@/components/customer/StarRating.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { formatPhp } from '@/lib/currency';
import customer from '@/routes/customer';
import type { CustomerProductListItem } from '@/types/customerProductList';

const props = defineProps<{
    product: CustomerProductListItem | null;
}>();

const open = defineModel<boolean>('open', { required: true });

const selectedSize = ref('');

const sizes = computed(() => props.product?.available_sizes ?? []);

watch(
    () => [props.product, open.value] as const,
    () => {
        if (!props.product || !open.value) {
            return;
        }

        const list = props.product.available_sizes;
        selectedSize.value = list.length > 0 ? list[0] : '';
    },
    { immediate: true },
);

const canSubmitCart = computed(() => {
    const p = props.product;

    if (!p || p.stock < 1) {
        return false;
    }

    if (sizes.value.length === 0) {
        return true;
    }

    return sizes.value.includes(selectedSize.value);
});
</script>

<template>
    <Sheet v-model:open="open">
        <SheetContent side="bottom" class="max-h-[min(92vh,680px)] gap-0 overflow-y-auto rounded-t-3xl px-0 pb-6 pt-2">
            <template v-if="product">
                <SheetHeader class="space-y-1 border-b border-border/60 px-5 pb-4 text-left">
                    <SheetTitle class="text-left text-lg font-semibold leading-snug sm:text-xl">
                        {{ product.name }}
                    </SheetTitle>
                    <SheetDescription class="text-left text-xs text-muted-foreground">
                        {{ product.category }}
                    </SheetDescription>
                </SheetHeader>

                <div class="space-y-5 px-5 pt-4">
                    <div
                        class="relative mx-auto flex aspect-square max-h-56 w-full max-w-sm items-center justify-center overflow-hidden rounded-2xl border border-border/50 bg-muted/20"
                    >
                        <div
                            class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_78%_72%_at_50%_55%,rgba(99,102,241,0.12),transparent_72%)]"
                        />
                        <img
                            v-if="product.image_url"
                            :src="product.image_url"
                            :alt="product.name"
                            class="relative z-[1] max-h-full max-w-full object-contain px-6 py-4"
                        />
                        <span v-else class="text-sm text-muted-foreground">No image</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <Badge v-if="product.is_featured" variant="secondary" class="text-[10px] uppercase">
                            Featured
                        </Badge>
                        <div class="flex items-center gap-2">
                            <StarRating :rating="Number(product.rating)" size="sm" />
                            <span class="text-xs tabular-nums text-muted-foreground">{{
                                Number(product.rating).toFixed(1)
                            }}</span>
                        </div>
                    </div>

                    <p class="text-2xl font-semibold tabular-nums">{{ formatPhp(product.price) }}</p>

                    <p
                        class="text-sm font-medium"
                        :class="product.stock > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive'"
                    >
                        {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                    </p>

                    <div v-if="product.color" class="text-sm">
                        <span class="font-medium text-foreground">Color</span>
                        <p class="mt-0.5 text-muted-foreground">{{ product.color }}</p>
                    </div>

                    <div v-if="sizes.length" class="space-y-2">
                        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Size (EU)</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="s in sizes"
                                :key="s"
                                type="button"
                                class="min-h-10 min-w-[2.75rem] rounded-xl border px-2.5 text-sm font-medium tabular-nums transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                :class="
                                    selectedSize === s
                                        ? 'border-primary bg-primary/10 text-primary'
                                        : 'border-border/80 bg-background hover:border-primary/40'
                                "
                                @click="selectedSize = s"
                            >
                                {{ s }}
                            </button>
                        </div>
                    </div>

                    <p
                        v-if="product.description"
                        class="whitespace-pre-wrap text-sm leading-relaxed text-muted-foreground"
                    >
                        {{ product.description }}
                    </p>
                    <p v-else class="text-sm italic text-muted-foreground">No description available.</p>

                    <div class="flex flex-col gap-2.5 border-t border-border/50 pt-4">
                        <Form v-bind="customer.cart.store.form()" #default="{ processing }">
                            <input type="hidden" name="product_id" :value="product.id" />
                            <input type="hidden" name="quantity" value="1" />
                            <input v-if="sizes.length" type="hidden" name="size" :value="selectedSize" />
                            <Button
                                type="submit"
                                variant="outline"
                                class="h-11 w-full rounded-xl"
                                :disabled="processing || !canSubmitCart"
                            >
                                <ShoppingBag class="mr-2 size-4" />
                                Add to cart
                            </Button>
                        </Form>
                        <Form v-bind="customer.orders.store.form()" #default="{ processing }">
                            <input type="hidden" name="product_id" :value="product.id" />
                            <input type="hidden" name="quantity" value="1" />
                            <Button type="submit" class="h-11 w-full rounded-xl" :disabled="processing || product.stock < 1">
                                <Zap class="mr-2 size-4" />
                                Order now
                            </Button>
                        </Form>
                        <Button variant="ghost" class="h-11 w-full rounded-xl text-muted-foreground" as-child>
                            <Link :href="customer.products.show.url(product.slug)">
                                <ExternalLink class="mr-2 size-4" />
                                Open full product page
                            </Link>
                        </Button>
                    </div>
                </div>
            </template>
        </SheetContent>
    </Sheet>
</template>
