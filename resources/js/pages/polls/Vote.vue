<script setup lang="ts">
import AlertError from '@/components/AlertError.vue';
import AlertSuccess from '@/components/AlertSuccess.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface PollOption {
    id: number;
    option_text: string;
    vote_count: number;
}

interface Poll {
    id: number;
    question: string;
    slug: string;
    expires_at: string | null;
    is_expired: boolean;
    total_votes: number;
    options: PollOption[];
}

interface Props {
    poll: Poll;
}

const props = defineProps<Props>();

// Form data
const selectedOptionId = ref<number | null>(null);
const email = ref('');
const isSubmitting = ref(false);

// Error and success states
const apiError = ref('');
const apiErrors = ref<string[]>([]);
const successMessage = ref('');
const showSuccess = ref(false);

// Computed properties
const isValid = computed(() => {
    return (
        selectedOptionId.value !== null &&
        email.value.trim() !== '' &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())
    );
});

// Form handlers
const handleSubmit = async () => {
    if (!isValid.value || isSubmitting.value || props.poll.is_expired) return;

    isSubmitting.value = true;
    apiError.value = '';
    apiErrors.value = [];

    const voteData = {
        poll_option_id: selectedOptionId.value,
        email: email.value.trim(),
    };

    try {
        const response = await fetch(`/api/polls/${props.poll.slug}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(voteData),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Show success message
            successMessage.value =
                data.message || 'Your vote has been submitted successfully!';
            showSuccess.value = true;

            // Reset form
            selectedOptionId.value = null;
            email.value = '';
        } else {
            isSubmitting.value = false;

            if (response.status === 422 && data.errors) {
                // Handle validation errors
                const allErrors: string[] = [];
                for (const field in data.errors) {
                    if (Array.isArray(data.errors[field])) {
                        allErrors.push(...data.errors[field]);
                    }
                }
                apiErrors.value = allErrors;
                apiError.value = '';
            } else {
                // Handle other errors
                apiError.value =
                    data.message || 'Failed to submit vote. Please try again.';
                apiErrors.value = [];
            }
        }
    } catch (error) {
        isSubmitting.value = false;
        apiError.value =
            'Network error. Please check your connection and try again.';
        apiErrors.value = [];
        console.error('Network error:', error);
    }
};

const formatExpiryMessage = (expiresAt: string | null) => {
    if (!expiresAt) return null;

    const date = new Date(expiresAt);
    const now = new Date();

    if (date < now) {
        return 'This poll has expired';
    } else {
        return `This poll expires on ${date.toLocaleDateString()}`;
    }
};
</script>

<template>
    <Head :title="`Vote: ${poll.question}`" />

    <!-- Minimal page layout -->
    <div class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-md">
            <!-- Success State -->
            <div v-if="showSuccess" class="text-center">
                <div class="mb-6">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100"
                    >
                        <svg
                            class="h-6 w-6 text-green-600"
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
                </div>

                <h1 class="mb-4 text-2xl font-bold text-gray-900">
                    Vote Submitted!
                </h1>

                <AlertSuccess
                    :messages="[successMessage]"
                    title="Thank you!"
                    class="mb-6"
                />

                <p class="mb-6 text-gray-600">
                    Your vote for "<strong>{{ poll.question }}</strong
                    >" has been recorded.
                </p>

                <Button
                    @click="
                        showSuccess = false;
                        isSubmitting = false;
                    "
                    variant="outline"
                    class="cursor-pointer"
                >
                    Vote Again
                </Button>
            </div>

            <!-- Voting Form -->
            <div v-else class="rounded-lg bg-white p-6 shadow">
                <!-- Poll Question -->
                <div class="mb-6">
                    <h1 class="mb-2 text-2xl font-bold text-gray-900">
                        {{ poll.question }}
                    </h1>

                    <!-- Expiry Notice -->
                    <p
                        v-if="formatExpiryMessage(poll.expires_at)"
                        :class="
                            poll.is_expired ? 'text-red-600' : 'text-gray-500'
                        "
                        class="text-sm"
                    >
                        {{ formatExpiryMessage(poll.expires_at) }}
                    </p>
                </div>

                <!-- Expired Poll Notice -->
                <div
                    v-if="poll.is_expired"
                    class="mb-6 rounded-md bg-red-50 p-4"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-red-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Poll Expired
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>This poll is no longer accepting votes.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voting Form (only if not expired) -->
                <form v-if="!poll.is_expired" @submit.prevent="handleSubmit">
                    <!-- Error Messages -->
                    <AlertError
                        v-if="apiErrors.length > 0"
                        :errors="apiErrors"
                        title="Validation Errors"
                        class="mb-4"
                    />
                    <AlertError
                        v-else-if="apiError"
                        :errors="[apiError]"
                        title="Error"
                        class="mb-4"
                    />

                    <!-- Poll Options -->
                    <div class="mb-6">
                        <Label
                            class="mb-3 block text-base font-medium text-gray-900"
                        >
                            Choose your option:
                        </Label>

                        <div class="space-y-3">
                            <div
                                v-for="option in poll.options"
                                :key="option.id"
                                class="flex items-center"
                            >
                                <input
                                    :id="`option-${option.id}`"
                                    v-model="selectedOptionId"
                                    :value="option.id"
                                    name="poll-option"
                                    type="radio"
                                    class="h-4 w-4 cursor-pointer border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <label
                                    :for="`option-${option.id}`"
                                    class="ml-3 flex-1 cursor-pointer py-2 text-sm font-medium text-gray-700"
                                >
                                    {{ option.option_text }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-6">
                        <Label
                            for="email"
                            class="mb-2 block text-base font-medium text-gray-900"
                        >
                            Your email address:
                        </Label>
                        <Input
                            id="email"
                            v-model="email"
                            type="email"
                            placeholder="Enter your email"
                            class="w-full cursor-pointer"
                            :disabled="isSubmitting"
                            required
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            Used to prevent duplicate voting. Your email won't
                            be shared.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <Button
                        type="submit"
                        :disabled="!isValid || isSubmitting"
                        class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg
                            v-if="isSubmitting"
                            class="mr-2 h-4 w-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        {{
                            isSubmitting ? 'Submitting Vote...' : 'Submit Vote'
                        }}
                    </Button>
                </form>

                <!-- Poll Stats (small footer) -->
                <div
                    class="mt-6 border-t pt-4 text-center text-sm text-gray-500"
                >
                    {{ poll.total_votes }}
                    {{ poll.total_votes === 1 ? 'vote' : 'votes' }} •
                    {{ poll.options.length }} options
                </div>
            </div>
        </div>
    </div>
</template>
