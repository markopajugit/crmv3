/**
 * Navigation Handler Utility
 * Handles navigation via data attributes
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Navigation handler may not work.');
}

/**
 * Initialize navigation handlers
 */
export function initNavigationHandler() {
    if (typeof window.$ === 'undefined') return;

    // Handle clickable rows with data-action="navigate"
    $(document).on('click', '[data-action="navigate"]', function() {
        const url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
}

// Auto-initialize
if (typeof window.$ !== 'undefined') {
    $(document).ready(function() {
        initNavigationHandler();
    });
}

