<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main
                class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row"
            >
                <div
                    class="flex-1 rounded-br-lg rounded-bl-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:rounded-tl-lg lg:rounded-br-none lg:p-12 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <h1
                        class="mb-6 text-center text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
                    >
                        Poll Creator
                    </h1>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                        Create engaging polls in seconds. Share with anyone
                        using a simple link. Get real-time results and insights
                        from your audience.
                    </p>

                    <!-- Features List -->
                    <ul class="mb-8 flex flex-col gap-3">
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Create polls with multiple options
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-green-100 dark:bg-green-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-green-600 dark:text-green-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Share via simple links - no signup required for
                                voters
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-purple-600 dark:text-purple-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                View real-time results with beautiful charts
                            </span>
                        </li>
                    </ul>

                    <!-- Auth Buttons -->
                    <template v-if="!$page.props.auth.user">
                        <div
                            class="flex flex-col justify-center gap-3 sm:flex-row"
                        >
                            <Link
                                :href="register()"
                                class="inline-block rounded-sm border border-[#1b1b18] bg-[#1b1b18] px-6 py-2.5 text-center text-sm leading-normal font-medium text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Get Started Free
                            </Link>
                            <Link
                                :href="login()"
                                class="inline-block rounded-sm border border-[#19140035] px-6 py-2.5 text-center text-sm leading-normal font-medium text-[#1b1b18] hover:border-[#1915014a] hover:bg-gray-50 dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b] dark:hover:bg-[#2a2a28]"
                            >
                                Sign In
                            </Link>
                        </div>
                    </template>
                    <template v-else>
                        <div class="mt-4 flex justify-center">
                            <Link
                                :href="dashboard()"
                                class="inline-block rounded-sm border border-[#1b1b18] bg-[#1b1b18] px-6 py-2.5 text-center text-sm leading-normal font-medium text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                            >
                                Go to Dashboard
                            </Link>
                        </div>
                    </template>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
