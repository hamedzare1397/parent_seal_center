<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/login';
// import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import {inject} from "vue";



defineProps<{
    status?: string;
    canResetPassword: boolean;
    bulletins?: Array<{
        id: number | string;
        title: string;
        body?: string;
        type?: 'info' | 'warning' | 'success' | 'danger';
        published_at?: string;
    }>;
    logoHref?: string | null;
}>();
const appName = inject('name');
const logoUrl = inject('logoUrl');
</script>

<template>
    <AuthBase :title="appName">
        <Head title="Login" />
        <section class="mx-auto flex w-full max-w-7xl flex-col gap-8 p-6 lg:flex-row">
            <!-- Left column: Logo + login form -->
            <div class="flex flex-col rounded-2xl border bg-white/70 p-8 shadow-sm backdrop-blur">
                <div class="mb-8 flex items-center justify-center">
                    <a
                        v-if="logoUrl"
                        :href="logoHref ?? '#'"
                        class="inline-flex items-center gap-3"
                    >
                        <img
                            :src="logoUrl"
                            alt="Application logo"
                            class="h-16 w-auto object-contain"
                        />
                    </a>
                </div>

                <div class="text-center">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        ورود به سامانه
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        لطفاً نام کاربری و رمز عبور خود را وارد کنید
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mt-4 rounded-md bg-green-50 p-3 text-sm font-medium text-green-700"
                >
                    {{ status }}
                </div>

                <Form
                    v-bind="store.form()"
                    :reset-on-success="['password']"
                    v-slot="{ errors, processing }"
                    class="mt-8 flex flex-col gap-6"
                >
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="username">نام کاربری</Label>
                            <Input
                                id="username"
                                type="text"
                                name="username"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="مثال: user123"
                            />
                            <InputError :message="errors.username" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password">رمز عبور</Label>
                                <TextLink
                                    v-if="canResetPassword"
                                    href=""
                                    class="text-sm text-primary-600"
                                >
                                    فراموشی رمز عبور؟
                                </TextLink>
                            </div>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <Label
                            for="remember"
                            class="flex items-center space-x-3 space-x-reverse text-sm text-gray-700"
                        >
                            <Checkbox id="remember" name="remember" />
                            <span>مرا به خاطر بسپار</span>
                        </Label>
                    </div>

                    <Button
                        type="submit"
                        class="mt-2 w-full"
                        :disabled="processing"
                    >
                        <Spinner v-if="processing" class="mr-2" />
                        ورود به سیستم
                    </Button>
                </Form>
            </div>

            <!-- Right column: bulletin board -->
            <aside
                class="flex w-full flex-col rounded-2xl border bg-slate-900/90 p-8 text-white shadow-md backdrop-blur lg:max-w-full"
            >
                <header class="mb-6">
                    <p class="text-sm uppercase tracking-wide text-slate-300">
                        تابلوی اعلانات
                    </p>
                    <h2 class="text-2xl font-semibold">آخرین اطلاعیه‌ها</h2>
                </header>

                <div v-if="bulletins?.length" class="space-y-4">
                    <article
                        v-for="bulletin in bulletins"
                        :key="bulletin.id"
                        class="rounded-xl border border-white/10 bg-white/5 p-4"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-medium">
                                {{ bulletin.title }}
                            </h3>
                            <span
                                v-if="bulletin.type"
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-blue-500/20 text-blue-200': bulletin.type === 'info',
                                    'bg-yellow-500/20 text-yellow-200': bulletin.type === 'warning',
                                    'bg-green-500/20 text-green-200': bulletin.type === 'success',
                                    'bg-red-500/20 text-red-200': bulletin.type === 'danger',
                                }"
                            >
                                {{ bulletin.type }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-slate-200">
                            {{ bulletin.body }}
                        </p>
                        <p
                            v-if="bulletin.published_at"
                            class="mt-3 text-xs text-slate-400"
                        >
                            {{ bulletin.published_at }}
                        </p>
                    </article>
                </div>

                <div
                    v-else
                    class="rounded-xl border border-dashed border-white/20 p-6 text-center text-sm text-slate-300"
                >
                    اطلاعیه‌ای ثبت نشده است
                </div>
            </aside>
        </section>
    </AuthBase>
</template>

---

## تغییرات مورد نیاز در سایر بخش‌ها

1. **استور یا روت لاگین (`@/routes/login` یا مشابه)**
- اطمینان حاصل کنید فیلد `username` در فرم پیش‌فرض وجود دارد:
```ts
const form = useForm({
username: '',
password: '',
remember: false,
});

