<script setup lang="ts">
import AlertError from '@/components/AlertError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { usePage } from '@inertiajs/vue3';
import { GripVertical, Plus, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    open: boolean;
}

interface Emits {
    (e: 'update:open', value: boolean): void;
    (e: 'success'): void;
}

interface CreatePollData {
    question: string;
    options: string[];
    expiresAt?: string;
}

interface PollOption {
    id: string;
    text: string;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

// Get page props for error handling
const page = usePage();

// Form data
const question = ref('');
const options = ref<PollOption[]>([
    { id: '1', text: '' },
    { id: '2', text: '' },
]);
const expiresAt = ref('');

// Form validation and loading state
const errors = ref<Record<string, string>>({});
const isSubmitting = ref(false);
const apiError = ref<string>('');
const apiErrors = ref<string[]>([]);

// Computed properties
const isValid = computed(() => {
    return (
        question.value.trim().length > 0 &&
        options.value.filter((opt) => opt.text.trim().length > 0).length >= 2
    );
});

const validOptions = computed(() => {
    return options.value.filter((opt) => opt.text.trim().length > 0);
});

// Methods
const addOption = () => {
    const newId = (options.value.length + 1).toString();
    options.value.push({ id: newId, text: '' });
};

const removeOption = (id: string) => {
    if (options.value.length > 2) {
        options.value = options.value.filter((opt) => opt.id !== id);
    }
};

const handleClose = () => {
    emit('update:open', false);
    resetForm();
};

const resetForm = () => {
    question.value = '';
    options.value = [
        { id: '1', text: '' },
        { id: '2', text: '' },
    ];
    expiresAt.value = '';
    errors.value = {};
    isSubmitting.value = false;
    apiError.value = '';
    apiErrors.value = [];
};

const validateForm = () => {
    errors.value = {};

    if (!question.value.trim()) {
        errors.value.question = 'Poll question is required';
    }

    const validOpts = validOptions.value;
    if (validOpts.length < 2) {
        errors.value.options = 'At least 2 options are required';
    }

    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm() || isSubmitting.value) return;

    isSubmitting.value = true;
    apiError.value = '';
    apiErrors.value = [];

    const pollData: CreatePollData = {
        question: question.value.trim(),
        options: validOptions.value.map((opt) => opt.text.trim()),
        expiresAt: expiresAt.value || undefined,
    };

    try {
        // Make API call to create poll
        const response = await fetch('/api/polls', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            credentials: 'same-origin', // Include cookies for session auth
            body: JSON.stringify(pollData),
        });

        const data = await response.json();

        if (response.ok && data.success) {
            // Poll created successfully
            emit('update:open', false);
            resetForm();
            // Emit success event for parent to show success message and handle refresh
            emit('success');
        } else {
            // Handle validation errors
            isSubmitting.value = false;

            if (response.status === 422 && data.errors) {
                // Handle 422 validation errors - extract all error messages
                const allErrors: string[] = [];
                for (const field in data.errors) {
                    if (Array.isArray(data.errors[field])) {
                        allErrors.push(...data.errors[field]);
                    }
                }
                apiErrors.value = allErrors;
                apiError.value = ''; // Clear single error message
            } else {
                // Handle other errors with single message
                apiError.value =
                    data.message || 'Failed to create poll. Please try again.';
                apiErrors.value = [];
            }
        }
    } catch (error) {
        // Handle network errors
        isSubmitting.value = false;
        apiError.value =
            'Network error. Please check your connection and try again.';
        apiErrors.value = [];
        console.error('Network error:', error);
    }
};

// Watch for server-side validation errors
watch(
    () => page.props.errors,
    (newErrors: any) => {
        if (newErrors && Object.keys(newErrors).length > 0) {
            errors.value = { ...newErrors };
            isSubmitting.value = false;
        }
    },
    { deep: true },
);

// Watch for successful form submission (when modal closes)
watch(
    () => props.open,
    (isOpen) => {
        if (!isOpen) {
            isSubmitting.value = false;
        }
    },
);

const handleCancel = () => {
    handleClose();
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent
            class="flex max-h-[90vh] max-w-3xl flex-col overflow-hidden"
        >
            <DialogHeader>
                <DialogTitle class="text-xl font-semibold"
                    >Create New Poll</DialogTitle
                >
            </DialogHeader>

            <!-- Error Alert -->
            <AlertError
                v-if="apiErrors.length > 0"
                :errors="apiErrors"
                title="Validation Errors"
                class="mx-1"
            />
            <AlertError
                v-else-if="apiError"
                :errors="[apiError]"
                title="Error"
                class="mx-1"
            />

            <div class="flex-1 space-y-6 overflow-y-auto pr-2">
                <!-- Poll Question -->
                <div class="space-y-2">
                    <Label for="question" class="text-sm font-medium">
                        Poll Question *
                    </Label>
                    <textarea
                        id="question"
                        v-model="question"
                        placeholder="What would you like to ask?"
                        class="flex min-h-[80px] w-full resize-none rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{
                            'border-red-500 focus:border-red-500 focus:ring-red-500/20':
                                errors.question,
                        }"
                    />
                    <p v-if="errors.question" class="text-sm text-red-600">
                        {{ errors.question }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ question.length }}/200 characters
                    </p>
                </div>

                <!-- Poll Options -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <Label class="text-sm font-medium">
                            Poll Options *
                        </Label>
                        <Button
                            type="button"
                            variant="outline"
                            size="sm"
                            @click="addOption"
                            class="cursor-pointer gap-1 text-xs"
                        >
                            <Plus class="h-3 w-3" />
                            Add Option
                        </Button>
                    </div>

                    <div class="space-y-2">
                        <div
                            v-for="(option, index) in options"
                            :key="option.id"
                            class="group flex items-center gap-2"
                        >
                            <!-- Drag Handle -->
                            <div class="cursor-move text-muted-foreground">
                                <GripVertical class="h-4 w-4" />
                            </div>

                            <!-- Option Number -->
                            <div
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-muted text-xs font-medium"
                            >
                                {{ index + 1 }}
                            </div>

                            <!-- Option Input -->
                            <Input
                                v-model="option.text"
                                :placeholder="`Option ${index + 1}`"
                                class="flex-1"
                            />

                            <!-- Remove Button -->
                            <Button
                                v-if="options.length > 2"
                                type="button"
                                variant="ghost"
                                size="sm"
                                @click="removeOption(option.id)"
                                class="h-8 w-8 cursor-pointer p-0 text-red-600 hover:bg-red-50 hover:text-red-700"
                                title="Remove option"
                            >
                                <X class="h-3.5 w-3.5" />
                            </Button>
                        </div>
                    </div>

                    <p v-if="errors.options" class="text-sm text-red-600">
                        {{ errors.options }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ validOptions.length }} valid options (minimum 2
                        required)
                    </p>
                </div>

                <!-- Poll Expiry (Optional) -->
                <div class="space-y-2">
                    <Label for="expires-at" class="text-sm font-medium">
                        Expiry Date (Optional)
                    </Label>
                    <Input
                        id="expires-at"
                        v-model="expiresAt"
                        type="datetime-local"
                        :min="new Date().toISOString().slice(0, 16)"
                        class="w-full"
                    />
                    <p class="text-xs text-muted-foreground">
                        Leave empty for polls that never expire
                    </p>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end gap-3 border-t pt-4">
                <Button
                    type="button"
                    variant="outline"
                    @click="handleCancel"
                    class="cursor-pointer"
                >
                    Cancel
                </Button>
                <Button
                    type="button"
                    @click="handleSubmit"
                    :disabled="!isValid || isSubmitting"
                    class="cursor-pointer gap-2"
                    :class="{ 'cursor-not-allowed': !isValid || isSubmitting }"
                >
                    <Plus v-if="!isSubmitting" class="h-4 w-4" />
                    <div
                        v-else
                        class="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"
                    ></div>
                    {{ isSubmitting ? 'Creating...' : 'Create Poll' }}
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
