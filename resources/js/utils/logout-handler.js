/**
 * Logout Handler Utility
 * Handles logout form submission
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Logout handler may not work.');
}

/**
 * Initialize logout handlers
 */
export function initLogoutHandler() {
    if (typeof window.$ === 'undefined') return;

    // Handle logout links with data-logout attribute
    $(document).on('click', '[data-action="logout"]', function(e) {
        e.preventDefault();
        const $form = $('#logout-form');
        if ($form.length) {
            $form.submit();
        }
    });
}

// Auto-initialize
if (typeof window.$ !== 'undefined') {
    $(document).ready(function() {
        initLogoutHandler();
    });
}

