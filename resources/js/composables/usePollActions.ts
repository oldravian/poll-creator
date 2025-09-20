import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Composable for poll-related actions and utilities
 * Handles poll operations like copying links, opening in new tabs, and success messages
 */
export function usePollActions() {
    // Copy link feedback state
    const copiedPollId = ref<number | null>(null);

    // Success message state
    const successMessage = ref('');

    /**
     * Copy poll link to clipboard with visual feedback
     * @param slug - Poll slug
     * @param pollId - Poll ID for feedback tracking
     */
    const copyPollLink = async (
        slug: string,
        pollId: number,
    ): Promise<void> => {
        const url = `${window.location.origin}/poll/${slug}`;
        try {
            await navigator.clipboard.writeText(url);
            // Show success feedback in the card
            copiedPollId.value = pollId;
            // Hide the feedback after 2 seconds
            setTimeout(() => {
                copiedPollId.value = null;
            }, 2000);
        } catch (err) {
            console.error('Failed to copy link:', err);
            // You could show an error message here if needed
        }
    };

    /**
     * Open poll in new tab with fallback handling
     * @param slug - Poll slug
     */
    const openPollInNewTab = (slug: string): void => {
        const url = `/poll/${slug}`;
        try {
            // Create a temporary anchor element and click it
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (err) {
            console.error('Failed to open poll in new tab:', err);
            // Fallback: try window.open if available
            if (typeof window !== 'undefined' && window.open) {
                window.open(url, '_blank', 'noopener,noreferrer');
            } else {
                // Last resort: navigate to the URL in the same tab
                window.location.href = url;
            }
        }
    };

    /**
     * Show success message with auto-hide
     * @param message - Success message to display
     * @param duration - Duration in milliseconds (default: 8000)
     */
    const showSuccessMessage = (
        message: string,
        duration: number = 8000,
    ): void => {
        successMessage.value = message;
        setTimeout(() => {
            successMessage.value = '';
        }, duration);
    };

    /**
     * Handle poll creation success with page refresh
     */
    const handlePollCreated = (): void => {
        // Refresh the polls data to show the new poll with success message
        setTimeout(() => {
            router.visit(window.location.pathname + '?success=poll_created', {
                preserveState: false,
                preserveScroll: true,
            });
        }, 100);
    };

    /**
     * Handle poll update success with page refresh
     */
    const handlePollUpdated = (): void => {
        // Refresh the polls data to show the updated poll with success message
        console.log('Triggering data refresh after poll update');

        // Pass success message as URL parameter to persist across refresh
        setTimeout(() => {
            router.visit(window.location.pathname + '?success=poll_updated', {
                preserveState: false,
                preserveScroll: true,
            });
        }, 100); // Small delay to ensure modal is fully closed
    };

    /**
     * Delete a poll via API
     * @param pollId - Poll ID to delete
     * @returns Promise resolving to success status
     */
    const deletePoll = async (pollId: number): Promise<boolean> => {
        try {
            const response = await fetch(`/api/polls/${pollId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Show success message and refresh
                setTimeout(() => {
                    router.visit(
                        window.location.pathname + '?success=poll_deleted',
                        {
                            preserveState: false,
                            preserveScroll: true,
                        },
                    );
                }, 100);
                return true;
            } else {
                console.error('Delete failed:', data.message);
                return false;
            }
        } catch (error) {
            console.error('Network error:', error);
            return false;
        }
    };

    /**
     * Process URL success parameters and show appropriate messages
     * Should be called on component mount
     */
    const processUrlSuccessMessages = (): void => {
        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');

        if (successParam === 'poll_created') {
            showSuccessMessage(
                'Poll created successfully! You can now share it with others.',
            );
            // Clean up URL by removing the success parameter
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        } else if (successParam === 'poll_updated') {
            showSuccessMessage('Poll updated successfully!');
            // Clean up URL by removing the success parameter
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        } else if (successParam === 'poll_deleted') {
            showSuccessMessage('Poll deleted successfully!');
            // Clean up URL by removing the success parameter
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        }
    };

    return {
        // State
        copiedPollId,
        successMessage,

        // Actions
        copyPollLink,
        openPollInNewTab,
        showSuccessMessage,
        handlePollCreated,
        handlePollUpdated,
        deletePoll,
        processUrlSuccessMessages,
    };
}
