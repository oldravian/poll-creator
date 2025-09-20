<script setup lang="ts">
import AlertSuccess from '@/components/AlertSuccess.vue';
import CreatePollModal from '@/components/polls/CreatePollModal.vue';
import PollDetailsModal from '@/components/polls/PollDetailsModal.vue';
import UpdatePollModal from '@/components/polls/UpdatePollModal.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useDateFormat } from '@/composables/useDateFormat';
import { usePollActions } from '@/composables/usePollActions';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    Calendar,
    Clock,
    Copy,
    Edit,
    ExternalLink,
    Eye,
    Plus,
    Trash2,
    Users,
} from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

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
const polls = page.props.polls as Poll[];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Polls Listing',
        href: dashboard().url,
    },
];

// Composables
const { formatDate, formatExpiryDate } = useDateFormat();
const {
    copiedPollId,
    successMessage,
    copyPollLink,
    openPollInNewTab,
    handlePollCreated,
    handlePollUpdated,
    deletePoll,
    processUrlSuccessMessages,
} = usePollActions();

// Modal state
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteConfirm = ref(false);
const showDetailsModal = ref(false);
const selectedPoll = ref<Poll | null>(null);

// Modal handlers
const openCreateModal = () => {
    showCreateModal.value = true;
};

const openUpdateModal = (poll: Poll) => {
    selectedPoll.value = poll;
    showUpdateModal.value = true;
};

const openDetailsModal = (poll: Poll) => {
    selectedPoll.value = poll;
    showDetailsModal.value = true;
};

const openDeleteConfirm = (poll: Poll) => {
    selectedPoll.value = poll;
    showDeleteConfirm.value = true;
};

const handleDeleteConfirm = async () => {
    if (!selectedPoll.value) return;

    const success = await deletePoll(selectedPoll.value.id);

    if (success) {
        // Close confirmation dialog
        showDeleteConfirm.value = false;
        selectedPoll.value = null;
    }
};

const handleDeleteCancel = () => {
    showDeleteConfirm.value = false;
    selectedPoll.value = null;
};

// Initialize success message handling on mount
onMounted(() => {
    processUrlSuccessMessages();
});
</script>

<template>
    <Head title="Polls Listing" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Success Message -->
        <AlertSuccess
            v-if="successMessage"
            :messages="[successMessage]"
            class="mx-6 mt-6"
        />
        <div
            v-else-if="page.props.flash?.success"
            class="mx-6 mt-6 rounded-md border border-green-200 bg-green-50 p-4"
        >
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ page.props.flash.success }}
                    </p>
                </div>
            </div>
        </div>

        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">My Polls</h1>
                    <p class="mt-1 text-muted-foreground">
                        Manage and track your polls
                    </p>
                </div>
                <Button
                    size="lg"
                    class="cursor-pointer gap-2"
                    @click="openCreateModal"
                >
                    <Plus class="h-4 w-4" />
                    Create New Poll
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-primary/10 p-2">
                            <BarChart3 class="h-4 w-4 text-primary" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Total Polls
                            </p>
                            <p class="text-2xl font-bold">{{ polls.length }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-green-500/10 p-2">
                            <Users class="h-4 w-4 text-green-600" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Total Votes
                            </p>
                            <p class="text-2xl font-bold">
                                {{
                                    polls.reduce(
                                        (sum, poll) => sum + poll.total_votes,
                                        0,
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="rounded-full bg-blue-500/10 p-2">
                            <Clock class="h-4 w-4 text-blue-600" />
                        </div>
                        <div>
                            <p
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Active Polls
                            </p>
                            <p class="text-2xl font-bold">
                                {{
                                    polls.filter((poll) => !poll.is_expired)
                                        .length
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Polls List -->
            <div class="space-y-4">
                <!-- Empty State -->
                <div v-if="polls.length === 0" class="py-12 text-center">
                    <div
                        class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-muted"
                    >
                        <BarChart3 class="h-8 w-8 text-muted-foreground" />
                    </div>
                    <h3 class="mb-2 text-lg font-semibold">No polls yet</h3>
                    <p class="mb-4 text-muted-foreground">
                        Create your first poll to get started
                    </p>
                    <Button @click="openCreateModal" class="cursor-pointer">
                        <Plus class="mr-2 h-4 w-4" />
                        Create New Poll
                    </Button>
                </div>

                <!-- Poll Cards -->
                <div
                    v-else
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >
                    <div
                        v-for="poll in polls"
                        :key="poll.id"
                        class="flex flex-col rounded-lg border bg-card p-4 shadow-sm transition-all duration-200 hover:shadow-md"
                    >
                        <!-- Poll Header -->
                        <div
                            class="mb-3 flex items-start justify-between gap-2"
                        >
                            <h3
                                class="min-w-0 flex-1 text-base leading-tight font-semibold text-foreground"
                            >
                                {{ poll.question }}
                            </h3>

                            <!-- Status Badge -->
                            <span
                                :class="[
                                    'inline-flex shrink-0 items-center rounded-full px-2 py-1 text-xs font-medium',
                                    poll.is_expired
                                        ? 'border border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300'
                                        : 'border border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300',
                                ]"
                            >
                                {{ poll.is_expired ? 'Expired' : 'Active' }}
                            </span>
                        </div>

                        <!-- Poll Metadata -->
                        <div
                            class="mb-3 flex-1 space-y-2 text-sm text-muted-foreground"
                        >
                            <div class="flex items-center gap-1">
                                <Calendar class="h-3 w-3 shrink-0" />
                                <span class="truncate">{{
                                    formatDate(poll.created_at)
                                }}</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <Users class="h-3 w-3 shrink-0" />
                                <span>{{ poll.total_votes }} votes</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <BarChart3 class="h-3 w-3 shrink-0" />
                                <span>{{ poll.options_count }} options</span>
                            </div>

                            <div
                                v-if="!poll.is_expired"
                                class="flex items-center gap-1"
                            >
                                <Clock class="h-3 w-3 shrink-0" />
                                <span class="truncate">{{
                                    formatExpiryDate(poll.expires_at)
                                }}</span>
                            </div>
                        </div>

                        <!-- Poll URL Preview -->
                        <div
                            class="mb-3 truncate rounded bg-muted px-2 py-1 font-mono text-xs text-muted-foreground"
                        >
                            /poll/{{ poll.slug }}
                        </div>

                        <!-- Copy Success Message -->
                        <div
                            v-if="copiedPollId === poll.id"
                            class="mb-3 flex items-center gap-2 rounded bg-green-50 px-3 py-2 text-sm text-green-700 transition-all duration-300"
                        >
                            <svg
                                class="h-4 w-4 text-green-600"
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
                            <span class="font-medium"
                                >Link copied to clipboard!</span
                            >
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-between gap-1 border-t pt-2"
                        >
                            <!-- Copy Link -->
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="copyPollLink(poll.slug, poll.id)"
                                class="h-8 w-8 flex-shrink-0 cursor-pointer p-0"
                                title="Copy poll link"
                            >
                                <Copy class="h-3.5 w-3.5" />
                            </Button>

                            <!-- External Link -->
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-8 flex-shrink-0 cursor-pointer p-0"
                                title="Open poll in new tab"
                                @click="openPollInNewTab(poll.slug)"
                            >
                                <ExternalLink class="h-3.5 w-3.5" />
                            </Button>

                            <!-- View Details -->
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="openDetailsModal(poll)"
                                class="h-8 w-8 flex-shrink-0 cursor-pointer p-0"
                                title="View poll details"
                            >
                                <Eye class="h-3.5 w-3.5" />
                            </Button>

                            <!-- Edit -->
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="openUpdateModal(poll)"
                                class="h-8 w-8 flex-shrink-0 cursor-pointer p-0 text-blue-600 hover:bg-blue-50 hover:text-blue-700"
                                title="Edit poll"
                            >
                                <Edit class="h-3.5 w-3.5" />
                            </Button>

                            <!-- Delete -->
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="openDeleteConfirm(poll)"
                                class="h-8 w-8 flex-shrink-0 cursor-pointer p-0 text-red-600 hover:bg-red-50 hover:text-red-700"
                                title="Delete poll"
                            >
                                <Trash2 class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Poll Modal -->
        <CreatePollModal
            v-model:open="showCreateModal"
            @success="handlePollCreated"
        />

        <!-- Update Poll Modal -->
        <UpdatePollModal
            v-model:open="showUpdateModal"
            :poll="selectedPoll"
            @success="handlePollUpdated"
        />

        <!-- Poll Details Modal -->
        <PollDetailsModal
            v-model:open="showDetailsModal"
            :poll="selectedPoll"
        />

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:open="showDeleteConfirm">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle class="text-xl font-semibold text-red-600">
                        Delete Poll
                    </DialogTitle>
                </DialogHeader>

                <div class="py-4">
                    <p class="text-gray-700">
                        Are you sure you want to delete this poll?
                    </p>
                    <div
                        v-if="selectedPoll"
                        class="mt-3 rounded-lg bg-gray-50 p-3"
                    >
                        <p class="font-medium text-gray-900">
                            "{{ selectedPoll.question }}"
                        </p>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ selectedPoll.total_votes }} votes •
                            {{ selectedPoll.options_count }} options
                        </p>
                    </div>
                    <p class="mt-3 text-sm text-red-600">
                        This action cannot be undone. All votes and poll data
                        will be permanently deleted.
                    </p>
                </div>

                <div class="flex justify-end gap-3 border-t pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleDeleteCancel"
                        class="cursor-pointer"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="button"
                        variant="destructive"
                        @click="handleDeleteConfirm"
                        class="cursor-pointer bg-red-600 hover:bg-red-700"
                    >
                        Delete Poll
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
