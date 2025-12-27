<script setup lang="ts">
import {Link, usePage} from '@inertiajs/vue3'

defineProps({
    auth: Object,
    breadcrumbs: {
        type: Array,
        default: () => []
    }
})

const page = usePage()
</script>

<template>
    <div dir="rtl" class="min-h-screen bg-gray-100 flex">

        <!-- ✅ Sidebar (Right) -->
        <aside class="w-64 bg-white border-l shadow-sm">

            <!-- Logo / Title -->
            <div class="h-16 flex items-center justify-center font-bold text-lg border-b">
                {{ page.props.name }}
            </div>

            <!-- Nav -->
            <nav class="p-4 space-y-2 text-sm">

                <Link
                    href="/dashboard"
                    class="block px-3 py-2 rounded hover:bg-gray-100"
                >
                    داشبورد
                </Link>

                <Link
                    href="/users"
                    class="block px-3 py-2 rounded hover:bg-gray-100"
                >
                    کاربران
                </Link>

                <Link
                    href="/settings"
                    class="block px-3 py-2 rounded hover:bg-gray-100"
                >
                    تنظیمات
                </Link>

            </nav>
        </aside>

        <!-- ✅ Main -->
        <div class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-6">
                <div class="text-sm text-gray-500">
                    خوش آمدید، {{ user?.name }}
                </div>

                <form method="post" action="/logout">
                    <button class="text-red-500 text-sm">خروج</button>
                </form>
            </header>

            <!-- ✅ Breadcrumb -->
            <div
                v-if="breadcrumbs.length"
                class="bg-gray-50 border-b px-6 py-3 text-sm"
            >
                <ol class="flex gap-2 text-gray-600">
                    <li
                        v-for="(item, index) in breadcrumbs"
                        :key="index"
                        class="flex items-center gap-2"
                    >
                        <span v-if="index > 0">/</span>

                        <Link
                            v-if="item.href"
                            :href="item.href"
                            class="text-blue-600 hover:underline"
                        >
                            {{ item.label }}
                        </Link>

                        <span v-else class="font-semibold text-gray-800">
              {{ item.label }}
            </span>
                    </li>
                </ol>
            </div>

            <!-- ✅ Page Content -->
            <main class="flex-1 p-6">
                <slot />
            </main>

        </div>
    </div>
</template>
