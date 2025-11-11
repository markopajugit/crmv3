/**
 * Error Handler Utility
 * Provides consistent error display and handling
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Error handler may not work.');
}

/**
 * Display error messages in the standard error container
 * @param {Object|Array|string} msg - Error message(s) to display
 */
export function printErrorMsg(msg) {
    if (typeof window.$ === 'undefined') return;

    const $errorContainer = $('.print-error-msg');
    
    if ($errorContainer.length === 0) {
        console.error('Error container (.print-error-msg) not found');
        return;
    }

    $errorContainer.find('ul').html('');
    $errorContainer.css('display', 'block');

    if (typeof msg === 'string') {
        $errorContainer.find('ul').append('<li>' + msg + '</li>');
    } else if (Array.isArray(msg)) {
        msg.forEach(function(error) {
            $errorContainer.find('ul').append('<li>' + error + '</li>');
        });
    } else if (typeof msg === 'object') {
        window.$.each(msg, function(key, value) {
            $errorContainer.find('ul').append('<li>' + value + '</li>');
        });
    }
}

/**
 * Hide error messages
 */
export function hideErrorMsg() {
    if (typeof window.$ === 'undefined') return;
    $('.print-error-msg').css('display', 'none');
}

/**
 * Show a simple alert message
 * @param {string} message - Message to display
 * @param {string} type - Alert type (success, error, warning, info)
 */
export function showAlert(message, type = 'info') {
    if (typeof window.$ === 'undefined') {
        alert(message);
        return;
    }

    const alertClass = 'alert-' + type;
    const $alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
        message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
        '</div>');

    // Try to find a container, otherwise prepend to body
    const $container = $('.container-fluid, .container, body').first();
    $container.prepend($alert);

    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $alert.fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}

/**
 * Handle AJAX error response
 * @param {Object} xhr - XMLHttpRequest object
 * @param {string} status - Error status
 * @param {string} error - Error message
 */
export function handleAjaxError(xhr, status, error) {
    let errorMessage = 'An error occurred';

    if (xhr.responseJSON) {
        if (xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseJSON.error) {
            if (typeof xhr.responseJSON.error === 'object') {
                printErrorMsg(xhr.responseJSON.error);
                return;
            } else {
                errorMessage = xhr.responseJSON.error;
            }
        } else if (xhr.responseJSON.errors) {
            printErrorMsg(xhr.responseJSON.errors);
            return;
        }
    } else if (xhr.responseText) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.message || response.error) {
                errorMessage = response.message || response.error;
            }
        } catch (e) {
            // Not JSON, use response text
            errorMessage = xhr.responseText;
        }
    }

    printErrorMsg(errorMessage);
}

