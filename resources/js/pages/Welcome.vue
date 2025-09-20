<script setup lang="ts">
import { login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a]"
    >
        <div class="text-center">
            <h1
                class="mb-8 text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
            >
                Welcome to Mini Apps
            </h1>
            <p class="mb-12 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                Choose your preferred mini application after logging in
            </p>

            <!-- Auth Buttons -->
            <div class="flex flex-col gap-4 sm:flex-row sm:justify-center">
                <Link
                    :href="register()"
                    class="inline-block rounded-md border border-[#1b1b18] bg-[#1b1b18] px-8 py-3 text-center text-base font-medium text-white transition-colors hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                >
                    Get Started Free
                </Link>
                <Link
                    :href="login()"
                    class="inline-block rounded-md border border-[#19140035] px-8 py-3 text-center text-base font-medium text-[#1b1b18] transition-colors hover:border-[#1915014a] hover:bg-gray-50 dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b] dark:hover:bg-[#2a2a28]"
                >
                    Sign In
                </Link>
            </div>
        </div>
    </div>
</template>
