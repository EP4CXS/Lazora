<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import PhoneOtpController from '@/actions/App/Http/Controllers/Auth/PhoneOtpController';

type Props = {
    phoneNumber?: string | null;
};

defineProps<Props>();

const page = usePage();
const user = page.props.auth?.user as { phone_number?: string | null } | undefined;
</script>

<template>
    <Head title="Verify phone" />

    <div class="mx-auto w-full max-w-md space-y-6">
        <div class="space-y-1">
            <h1 class="text-xl font-semibold">Verify your phone number</h1>
            <p class="text-sm text-muted-foreground">
                Enter the 6-digit code sent to
                <span class="font-medium text-foreground">{{ phoneNumber ?? user?.phone_number }}</span>
            </p>
        </div>

        <Form v-bind="PhoneOtpController.verify.form()" v-slot="{ errors, processing }" class="space-y-4">
            <div class="grid gap-2">
                <Label for="otp">OTP code</Label>
                <Input
                    id="otp"
                    name="otp"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    placeholder="123456"
                    required
                    maxlength="6"
                />
                <InputError :message="errors.otp" />
            </div>

            <div class="flex gap-3">
                <Button type="submit" :disabled="processing">
                    <Spinner v-if="processing" />
                    Verify
                </Button>

                <Button type="button" variant="outline" :disabled="processing" @click="PhoneOtpController.resend.visit()">
                    Resend code
                </Button>
            </div>
        </Form>
    </div>
</template>

