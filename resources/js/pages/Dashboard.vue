<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'

defineProps({
    auth:Object,
})
const page = usePage()
</script>

<template>
    <AppLayout :user="auth">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-bold text-gray-700 mb-2">کاربران</h2>
                <p class="text-2xl font-bold text-blue-600"></p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-bold text-gray-700 mb-2">سفارش‌ها</h2>
                <p class="text-2xl font-bold text-green-600">۴۵</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="font-bold text-gray-700 mb-2">پیام‌ها</h2>
                <p class="text-2xl font-bold text-purple-600">۸</p>
            </div>
            <pre dir="ltr">
                {{ page.props.auth }}
            </pre>

        </div>
    </AppLayout>
</template>
