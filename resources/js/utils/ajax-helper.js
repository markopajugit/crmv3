/**
 * AJAX Helper Utilities
 * Provides standardized AJAX request handling and error display functions
 */

/**
 * Get CSRF token from meta tag
 */
function getCsrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

/**
 * Wrapper for consistent AJAX calls with CSRF token
 * @param {Object} options - jQuery AJAX options
 * @returns {jQuery.jqXHR}
 */
function ajaxRequest(options) {
    const defaults = {
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        contentType: 'application/json',
        processData: false
    };

    // Merge user options with defaults
    const config = $.extend(true, {}, defaults, options);

    // Convert data object to JSON string if it's an object
    if (config.data && typeof config.data === 'object' && !(config.data instanceof FormData)) {
        config.data = JSON.stringify(config.data);
    } else if (config.data && typeof config.data === 'object') {
        // For FormData, add CSRF token
        if (config.data instanceof FormData) {
            config.data.append('_token', getCsrfToken());
        }
        // For regular form data, add token
        if (!config.data._token) {
            config.data._token = getCsrfToken();
        }
        // Don't stringify form data
        config.processData = false;
        config.contentType = false;
    }

    return $.ajax(config);
}

/**
 * Standardized success/error handling for AJAX responses
 * @param {Object} response - AJAX response object
 * @param {Function} onSuccess - Success callback
 * @param {Function} onError - Error callback (optional)
 * @returns {boolean} - True if successful, false otherwise
 */
function handleAjaxResponse(response, onSuccess, onError) {
    if (response.success) {
        if (typeof onSuccess === 'function') {
            onSuccess(response.data, response.message);
        }
        return true;
    } else {
        if (typeof onError === 'function') {
            onError(response.error);
        } else {
            showError(response.error);
        }
        return false;
    }
}

/**
 * Update field value in DOM after successful save
 * @param {jQuery|string} selector - jQuery selector or element
 * @param {string|function} value - Value to set or function that returns value
 * @param {Object} options - Additional options
 */
function updateFieldValue(selector, value, options = {}) {
    const $element = $(selector);
    if ($element.length === 0) {
        console.warn('Element not found:', selector);
        return;
    }

    const finalValue = typeof value === 'function' ? value() : value;

    if ($element.is('input, textarea, select')) {
        $element.val(finalValue);
    } else {
        $element.html(finalValue || '');
    }

    // Trigger change event if specified
    if (options.triggerChange !== false) {
        $element.trigger('change');
    }

    // Add visual feedback
    if (options.showFeedback !== false) {
        $element.addClass('updated');
        setTimeout(() => {
            $element.removeClass('updated');
        }, 1000);
    }
}

/**
 * Centralized error display (replaces printErrorMsg)
 * @param {Object|string} error - Error object or error message
 */
function showError(error) {
    // Clear previous errors
    $('.print-error-msg').remove();
    $('.alert-danger').remove();

    let errorHtml = '<div class="alert alert-danger print-error-msg" role="alert"><ul class="mb-0">';

    if (typeof error === 'string') {
        errorHtml += '<li>' + escapeHtml(error) + '</li>';
    } else if (typeof error === 'object') {
        if (Array.isArray(error)) {
            error.forEach(err => {
                errorHtml += '<li>' + escapeHtml(err) + '</li>';
            });
        } else {
            // Handle Laravel validation errors format
            Object.keys(error).forEach(key => {
                const messages = Array.isArray(error[key]) ? error[key] : [error[key]];
                messages.forEach(msg => {
                    errorHtml += '<li><strong>' + escapeHtml(key) + ':</strong> ' + escapeHtml(msg) + '</li>';
                });
            });
        }
    }

    errorHtml += '</ul></div>';

    // Insert error at the top of the content area
    const $contentArea = $('.body .container-fluid').first();
    if ($contentArea.length) {
        $contentArea.prepend(errorHtml);
    } else {
        $('body').prepend(errorHtml);
    }

    // Scroll to error
    $('html, body').animate({
        scrollTop: $('.print-error-msg').offset().top - 100
    }, 500);

    // Auto-hide after 10 seconds
    setTimeout(() => {
        $('.print-error-msg').fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000);
}

/**
 * Show success message
 * @param {string} message - Success message
 */
function showSuccess(message) {
    // Clear previous messages
    $('.alert-success').not('.alert-success:first').remove();

    const successHtml = '<div class="alert alert-success" role="alert">' + escapeHtml(message) + '</div>';

    // Insert success message at the top of the content area
    const $contentArea = $('.body .container-fluid').first();
    if ($contentArea.length) {
        $contentArea.prepend(successHtml);
    } else {
        $('body').prepend(successHtml);
    }

    // Auto-hide after 5 seconds
    setTimeout(() => {
        $('.alert-success').not('.alert-success:first').fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}

/**
 * Escape HTML to prevent XSS
 * @param {string} text - Text to escape
 * @returns {string} - Escaped text
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

/**
 * Show loading state on element
 * @param {jQuery|string} selector - Element to show loading on
 * @param {string} text - Loading text (optional)
 */
function showLoading(selector, text = 'Loading...') {
    const $element = $(selector);
    $element.prop('disabled', true);
    if ($element.is('button')) {
        const originalText = $element.html();
        $element.data('original-text', originalText);
        $element.html('<i class="fa fa-spinner fa-spin"></i> ' + text);
    }
    $element.addClass('loading');
}

/**
 * Hide loading state on element
 * @param {jQuery|string} selector - Element to hide loading on
 */
function hideLoading(selector) {
    const $element = $(selector);
    $element.prop('disabled', false);
    if ($element.is('button')) {
        const originalText = $element.data('original-text');
        if (originalText) {
            $element.html(originalText);
        }
    }
    $element.removeClass('loading');
}

// Export functions for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ajaxRequest,
        handleAjaxResponse,
        updateFieldValue,
        showError,
        showSuccess,
        escapeHtml,
        showLoading,
        hideLoading,
        getCsrfToken
    };
}

