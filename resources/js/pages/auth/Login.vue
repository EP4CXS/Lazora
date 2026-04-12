<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import {
    authDarkFooterLinkClass,
    authDarkInputClass,
    authDarkPrimaryButtonClass,
    authDarkSocialButtonClass,
} from '@/lib/authUi';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import social from '@/routes/social';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2.5 text-center text-[13px] font-medium text-emerald-100 sm:mb-5 sm:px-4 sm:py-3 sm:text-sm"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-4 sm:gap-6"
    >
        <div class="grid gap-4 sm:gap-5 md:gap-6">
            <div class="grid gap-1.5 sm:gap-2">
                <Label
                    for="email"
                    class="text-[13px] font-medium text-white sm:text-sm"
                >
                    Email address
                </Label>
                <Input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    placeholder="email@example.com"
                    :class="authDarkInputClass"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-1.5 sm:gap-2">
                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between sm:gap-2">
                    <Label
                        for="password"
                        class="text-[13px] font-medium text-white sm:text-sm"
                    >
                        Password
                    </Label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        :tabindex="5"
                        :class="authDarkFooterLinkClass"
                        class="text-sm"
                    >
                        Forgot password?
                    </TextLink>
                </div>
                <PasswordInput
                    id="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="Password"
                    :class="authDarkInputClass"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="flex items-center">
                <Label
                    for="remember"
                    class="flex cursor-pointer items-center gap-3 text-sm font-normal text-zinc-400"
                >
                    <Checkbox
                        id="remember"
                        name="remember"
                        :tabindex="3"
                        class="border-white/30 data-[state=checked]:border-white data-[state=checked]:bg-white data-[state=checked]:text-black focus-visible:ring-white/50"
                    />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
                :class="authDarkPrimaryButtonClass"
            >
                <Spinner v-if="processing" />
                Log in
            </Button>

            <div class="relative my-0.5 py-1.5 sm:my-1 sm:py-2">
                <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-zinc-800" />
                <span
                    class="relative mx-auto block w-fit bg-[#050508] px-2 text-center text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-zinc-500 sm:px-3 sm:text-[0.65rem]"
                >
                    or continue with
                </span>
            </div>

            <Button
                type="button"
                variant="outline"
                :tabindex="5"
                as="a"
                :href="social.redirect.url('google')"
                :class="authDarkSocialButtonClass"
            >
                <svg class="mr-2 h-4 w-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"
                        fill="#4285F4"
                    />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853"
                    />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05"
                    />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335"
                    />
                </svg>
                Continue with Google
            </Button>
        </div>

        <div
            v-if="canRegister"
            class="text-center text-[13px] leading-relaxed text-zinc-500 sm:text-sm"
        >
            Don't have an account?
            <TextLink :href="register()" :tabindex="5" :class="authDarkFooterLinkClass">
                Sign up
            </TextLink>
        </div>
    </Form>
</template>
