/**
 * AJAX Helper Utility
 * Provides centralized AJAX configuration and request handling
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. AJAX helper may not work.');
}

/**
 * Setup AJAX defaults with CSRF token
 */
export function setupAjaxDefaults() {
    if (typeof window.$ === 'undefined') return;
    
    window.$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

/**
 * Get CSRF token
 */
export function getCsrfToken() {
    if (typeof window.$ === 'undefined') return '';
    return $('meta[name="csrf-token"]').attr('content') || '';
}

/**
 * Make an AJAX request with standardized error handling
 * @param {Object} options - AJAX options
 * @param {string} options.url - Request URL
 * @param {string} options.method - HTTP method (GET, POST, PUT, DELETE)
 * @param {Object} options.data - Request data
 * @param {Function} options.success - Success callback
 * @param {Function} options.error - Error callback
 * @param {boolean} options.reloadOnSuccess - Whether to reload page on success (default: false)
 */
export function ajaxRequest(options) {
    if (typeof window.$ === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }

    const defaults = {
        method: 'GET',
        reloadOnSuccess: false,
        showErrors: true
    };

    const config = { ...defaults, ...options };

    // Ensure CSRF token is included
    if (!config.headers) {
        config.headers = {};
    }
    if (!config.headers['X-CSRF-TOKEN']) {
        config.headers['X-CSRF-TOKEN'] = getCsrfToken();
    }

    return window.$.ajax({
        url: config.url,
        type: config.method,
        data: config.data,
        headers: config.headers,
        success: function(data) {
            if (config.reloadOnSuccess) {
                window.location.reload();
            } else if (config.success) {
                config.success(data);
            }
        },
        error: function(xhr, status, error) {
            if (config.showErrors && config.error) {
                config.error(xhr, status, error);
            } else if (config.error) {
                config.error(xhr, status, error);
            }
        }
    });
}

/**
 * Initialize AJAX helper
 */
export function initAjaxHelper() {
    setupAjaxDefaults();
}

// Auto-initialize when module loads
if (typeof window.$ !== 'undefined') {
    initAjaxHelper();
}

