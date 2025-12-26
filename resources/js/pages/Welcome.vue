<script setup lang="ts">
import { dashboard, login } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import {inject} from "vue";

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
const appName = inject('name');
</script>

<template>
    <Head :title="`ورود به سامانه ${appName}`">
    </Head>
    <h1>hello</h1>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header
            class="mb-6 w-full max-w-[700px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link> نام</Link>
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    داشبورد
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >ورود
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        قبت نام
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="container">

                <section class="intro">
                    <h2>چرا روانشناسی کودک مهم است؟</h2>
                    <p>
                        سال‌های اولیه زندگی کودک، پایه‌گذار شخصیت، احساسات و روابط آینده اوست.
                        در این سایت تلاش می‌کنیم مفاهیم علمی روانشناسی کودک را به زبان ساده
                        برای پدر و مادرها توضیح دهیم.
                    </p>
                </section>

                <section class="cards">
                    <div class="card">
                        <h3>👶 رشد روانی بر اساس سن</h3>
                        <p>بررسی نیازهای عاطفی و رفتاری کودک از تولد تا نوجوانی</p>
                        <button>مشاهده</button>
                    </div>

                    <div class="card">
                        <h3>😟 مشکلات رفتاری</h3>
                        <p>لجبازی، پرخاشگری، اضطراب، شب‌ادراری و راهکارهای علمی</p>
                        <button>مطالعه</button>
                    </div>

                    <div class="card">
                        <h3>📝 تست‌های روانشناسی</h3>
                        <p>آزمون‌های ساده برای شناخت بهتر فرزندتان</p>
                        <button>شروع تست</button>
                    </div>
                </section>

            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>

<style>
body {
    font-family: Vazirmatn, sans-serif;
    margin: 0;
    background: #f9f9f9;
    color: #333;
}

.header {
    background: #6bb7c7;
    color: #fff;
    text-align: center;
    padding: 30px;
}

.nav {
    display: flex;
    justify-content: center;
    background: #4fa3b4;
}

.nav a {
    color: white;
    padding: 15px 20px;
    text-decoration: none;
}

.nav a:hover {
    background: #3b8e9f;
}

.container {
    padding: 30px;
    max-width: 1100px;
    margin: auto;
}

.intro {
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    flex: 1;
    min-width: 250px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.card h3 {
    color: #4fa3b4;
}

.card button {
    margin-top: 10px;
    padding: 8px 15px;
    border: none;
    background: #6bb7c7;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

.card button:hover {
    background: #4fa3b4;
}

.footer {
    text-align: center;
    padding: 15px;
    background: #eee;
    margin-top: 40px;
}

</style>
