<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    BarChart3,
    Calendar,
    Clock,
    Copy,
    ExternalLink,
    Users,
} from 'lucide-vue-next';

interface Props {
    open: boolean;
    poll?: Poll | null;
}

interface Emits {
    (e: 'update:open', value: boolean): void;
}

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

const props = withDefaults(defineProps<Props>(), {
    poll: null,
});

const emit = defineEmits<Emits>();

// Utility functions
const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInDays = Math.floor(
        (now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24),
    );

    if (diffInDays === 0) {
        return 'Today';
    } else if (diffInDays === 1) {
        return 'Yesterday';
    } else if (diffInDays < 7) {
        return `${diffInDays} days ago`;
    } else {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    }
};

const formatExpiryDate = (dateString: string | null) => {
    if (!dateString) return 'Never expires';

    const date = new Date(dateString);
    const now = new Date();

    if (date < now) {
        return 'Expired';
    } else {
        return `Expires ${date.toLocaleDateString()}`;
    }
};

const copyPollLink = async () => {
    if (!props.poll) return;

    const origin =
        typeof window !== 'undefined' && window.location
            ? window.location.origin
            : '';
    const url = `${origin}/poll/${props.poll.slug}`;
    try {
        await navigator.clipboard.writeText(url);
        console.log('Link copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy link:', err);
    }
};

const openPollInNewTab = () => {
    if (!props.poll) return;

    const url = `/poll/${props.poll.slug}`;
    try {
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (err) {
        console.error('Failed to open poll in new tab:', err);
        if (typeof window !== 'undefined' && window.open) {
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    }
};

const getVotePercentage = (optionVotes: number, totalVotes: number): number => {
    if (totalVotes === 0) return 0;
    return Math.round((optionVotes / totalVotes) * 100);
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent
            class="flex max-h-[90vh] max-w-2xl flex-col overflow-hidden"
        >
            <DialogHeader>
                <DialogTitle class="pr-8 text-xl font-semibold">
                    Poll Details
                </DialogTitle>
            </DialogHeader>

            <div v-if="poll" class="flex-1 space-y-6 overflow-y-auto">
                <!-- Poll Question -->
                <div>
                    <h2 class="mb-3 text-lg font-bold text-gray-900">
                        {{ poll.question }}
                    </h2>

                    <!-- Poll Metadata -->
                    <div
                        class="flex flex-wrap items-center gap-4 text-sm text-gray-600"
                    >
                        <div class="flex items-center gap-2">
                            <Calendar class="h-4 w-4" />
                            <span>{{ formatDate(poll.created_at) }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <Users class="h-4 w-4" />
                            <span>{{ poll.total_votes }} votes</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <BarChart3 class="h-4 w-4" />
                            <span>{{ poll.options_count }} options</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <Clock class="h-4 w-4" />
                            <Badge
                                :variant="
                                    poll.is_expired ? 'destructive' : 'default'
                                "
                                class="text-xs"
                            >
                                {{ formatExpiryDate(poll.expires_at) }}
                            </Badge>
                        </div>
                    </div>
                </div>

                <!-- Poll URL -->
                <div v-if="typeof window !== 'undefined' && window.location">
                    <h3 class="mb-2 text-sm font-medium text-gray-900">
                        Poll Link
                    </h3>
                    <div
                        class="flex items-center gap-2 rounded-lg bg-gray-50 p-3"
                    >
                        <code class="flex-1 font-mono text-sm text-gray-700">
                            {{ window.location.origin }}/poll/{{ poll.slug }}
                        </code>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="copyPollLink"
                            class="h-8 w-8 cursor-pointer p-0"
                            title="Copy link"
                        >
                            <Copy class="h-3.5 w-3.5" />
                        </Button>
                    </div>
                </div>

                <!-- Poll Options with Results -->
                <div>
                    <h3 class="mb-3 text-sm font-medium text-gray-900">
                        Poll Options & Results
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="(option, index) in poll.options"
                            :key="option.id"
                            class="rounded-lg border bg-white p-4"
                        >
                            <div class="mb-2 flex items-center justify-between">
                                <span class="font-medium text-gray-900">
                                    {{ index + 1 }}. {{ option.option_text }}
                                </span>
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600"
                                >
                                    <span>{{ option.vote_count }} votes</span>
                                    <Badge variant="secondary" class="text-xs">
                                        {{
                                            getVotePercentage(
                                                option.vote_count,
                                                poll.total_votes,
                                            )
                                        }}%
                                    </Badge>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="h-2 w-full rounded-full bg-gray-200">
                                <div
                                    class="h-2 rounded-full bg-blue-600 transition-all duration-300"
                                    :style="{
                                        width: `${getVotePercentage(option.vote_count, poll.total_votes)}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- No votes message -->
                        <div
                            v-if="poll.total_votes === 0"
                            class="py-4 text-center"
                        >
                            <p class="text-sm text-gray-500">
                                No votes yet. Be the first to vote!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex items-center justify-between border-t pt-4">
                <Button
                    variant="outline"
                    @click="emit('update:open', false)"
                    class="cursor-pointer"
                >
                    Close
                </Button>

                <div class="flex gap-2">
                    <Button
                        v-if="typeof window !== 'undefined' && window.location"
                        variant="outline"
                        @click="copyPollLink"
                        class="cursor-pointer gap-2"
                    >
                        <Copy class="h-4 w-4" />
                        Copy Link
                    </Button>

                    <Button
                        @click="openPollInNewTab"
                        class="cursor-pointer gap-2"
                    >
                        <ExternalLink class="h-4 w-4" />
                        Open Poll
                    </Button>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
