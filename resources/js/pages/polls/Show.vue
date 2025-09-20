<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { BarChart3, Calendar, Clock, Users } from 'lucide-vue-next';

interface Poll {
    id: number;
    user_id: number;
    question: string;
    slug: string;
    expires_at: string | null;
    created_at: string;
    updated_at: string;
    is_expired: boolean;
    total_votes: number;
    options_count: number;
    options: PollOption[];
}

interface PollOption {
    id: number;
    option_text: string;
    vote_count: number;
}

const page = usePage();
const poll = page.props.poll as Poll;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Polls',
        href: '#',
    },
    {
        title:
            poll.question.length > 50
                ? poll.question.substring(0, 50) + '...'
                : poll.question,
        href: '#',
    },
];

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="poll.question" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl p-6">
            <!-- Poll Header -->
            <div class="mb-8 rounded-lg bg-white p-6 shadow-sm">
                <h1 class="mb-4 text-2xl font-bold text-gray-900">
                    {{ poll.question }}
                </h1>

                <div
                    class="flex flex-wrap items-center gap-4 text-sm text-gray-600"
                >
                    <div class="flex items-center gap-2">
                        <Calendar class="h-4 w-4" />
                        <span>Created {{ formatDate(poll.created_at) }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <Users class="h-4 w-4" />
                        <span>{{ poll.total_votes }} votes</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <BarChart3 class="h-4 w-4" />
                        <span>{{ poll.options_count }} options</span>
                    </div>

                    <div v-if="poll.expires_at" class="flex items-center gap-2">
                        <Clock class="h-4 w-4" />
                        <span
                            :class="
                                poll.is_expired
                                    ? 'text-red-600'
                                    : 'text-green-600'
                            "
                        >
                            {{ poll.is_expired ? 'Expired' : 'Active' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Poll Options -->
            <div class="mb-8 rounded-lg bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    Poll Options
                </h2>

                <div class="space-y-3">
                    <div
                        v-for="option in poll.options"
                        :key="option.id"
                        class="flex items-center justify-between rounded-lg border p-4 hover:bg-gray-50"
                    >
                        <span class="font-medium text-gray-900">
                            {{ option.option_text }}
                        </span>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">
                                {{ option.vote_count }} votes
                            </span>
                            <div class="h-2 w-24 rounded-full bg-gray-200">
                                <div
                                    class="h-2 rounded-full bg-blue-600 transition-all duration-300"
                                    :style="{
                                        width:
                                            poll.total_votes > 0
                                                ? `${(option.vote_count / poll.total_votes) * 100}%`
                                                : '0%',
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voting Section (Placeholder) -->
            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    Cast Your Vote
                </h2>

                <div class="mb-4 rounded-lg bg-blue-50 p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Coming Soon:</strong> Voting functionality will
                        be implemented in the next phase. For now, you can view
                        the poll results and options.
                    </p>
                </div>

                <Button disabled class="cursor-not-allowed opacity-50">
                    Vote (Coming Soon)
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
