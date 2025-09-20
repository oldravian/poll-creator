<script setup lang="ts">
import { dashboard } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Choose Your App">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div class="mb-12 text-center">
            <h1
                class="mb-4 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
            >
                Choose Your Mini Application
            </h1>
            <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                Select the application you'd like to use today
            </p>
        </div>

        <div
            class="flex w-full max-w-6xl flex-col gap-8 lg:flex-row lg:justify-center"
        >
            <!-- Poll Creator Card -->
            <div
                class="flex w-full max-w-md flex-col overflow-hidden rounded-lg lg:max-w-lg"
            >
                <div
                    class="flex-1 rounded-lg bg-white p-6 pb-8 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:p-8 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <h2
                        class="mb-6 text-center text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
                    >
                        Poll Creator
                    </h2>
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
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-orange-600 dark:text-orange-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Set expiration dates for polls
                            </span>
                        </li>
                    </ul>

                    <!-- Go to Poll Creator Button -->
                    <div class="flex justify-center">
                        <Link
                            :href="dashboard()"
                            class="inline-block rounded-sm border border-[#1b1b18] bg-[#1b1b18] px-6 py-2.5 text-center text-sm leading-normal font-medium text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                        >
                            Go to Poll Creator
                        </Link>
                    </div>
                </div>
            </div>

            <!-- To-Do List Card -->
            <div
                class="flex w-full max-w-md flex-col overflow-hidden rounded-lg lg:max-w-lg"
            >
                <div
                    class="flex-1 rounded-lg bg-white p-6 pb-8 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:p-8 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"
                >
                    <h2
                        class="mb-6 text-center text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
                    >
                        To-Do List
                    </h2>
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                        Organize your tasks efficiently. Create, manage, and
                        prioritize your daily activities with a clean and
                        intuitive interface.
                    </p>

                    <!-- Features List -->
                    <ul class="mb-8 flex flex-col gap-3">
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
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Create and manage task lists
                            </span>
                        </li>
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
                                Add and complete tasks easily
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-red-100 dark:bg-red-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-red-600 dark:text-red-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Delete completed or unwanted tasks
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900"
                            >
                                <svg
                                    class="h-3.5 w-3.5 text-yellow-600 dark:text-yellow-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
                                Prioritize tasks with drag & drop
                            </span>
                        </li>
                    </ul>

                    <!-- Coming Soon Notice -->
                    <div class="flex justify-center">
                        <div
                            class="inline-block rounded-sm border border-gray-300 bg-gray-100 px-6 py-2.5 text-center text-sm leading-normal font-medium text-gray-600 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                        >
                            Coming Soon
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
