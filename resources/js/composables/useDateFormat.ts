/**
 * Composable for date formatting utilities
 * Provides consistent date formatting across the application
 */
export function useDateFormat() {
    /**
     * Format a date string to show relative time (e.g., "2 hours ago", "Yesterday")
     * @param dateString - ISO date string
     * @returns Formatted relative date string
     */
    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInDays = Math.floor(
            (now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24),
        );

        if (diffInDays === 0) {
            const diffInHours = Math.floor(
                (now.getTime() - date.getTime()) / (1000 * 60 * 60),
            );
            if (diffInHours === 0) {
                return 'Just now';
            }
            return `${diffInHours} hour${diffInHours === 1 ? '' : 's'} ago`;
        } else if (diffInDays === 1) {
            return 'Yesterday';
        } else if (diffInDays < 7) {
            return `${diffInDays} days ago`;
        } else {
            return date.toLocaleDateString();
        }
    };

    /**
     * Format expiry date to show when a poll expires
     * @param dateString - ISO date string or null
     * @returns Formatted expiry message
     */
    const formatExpiryDate = (dateString: string | null): string => {
        if (!dateString) return 'Never expires';

        const date = new Date(dateString);
        const now = new Date();
        const diffInDays = Math.ceil(
            (date.getTime() - now.getTime()) / (1000 * 60 * 60 * 24),
        );

        if (diffInDays < 0) {
            return 'Expired';
        } else if (diffInDays === 0) {
            return 'Expires today';
        } else if (diffInDays === 1) {
            return 'Expires tomorrow';
        } else if (diffInDays < 7) {
            return `Expires in ${diffInDays} days`;
        } else {
            return `Expires ${date.toLocaleDateString()}`;
        }
    };

    /**
     * Format a date to show "Today", "Yesterday", or full date
     * Used for poll details modal
     * @param dateString - ISO date string
     * @returns Formatted date string
     */
    const formatDateSimple = (dateString: string): string => {
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

    return {
        formatDate,
        formatExpiryDate,
        formatDateSimple,
    };
}
