/**
 * Datepicker Utility
 * Provides standardized datepicker initialization
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Datepicker utility may not work.');
}

/**
 * Default datepicker configuration
 */
const defaultConfig = {
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
    dateFormat: "dd.mm.yy",
    constrainInput: false
};

/**
 * Initialize datepicker on an element
 * @param {string|jQuery} selector - Element selector or jQuery object
 * @param {Object} options - Additional datepicker options
 */
export function initDatepicker(selector, options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = { ...defaultConfig, ...options };
    const $element = typeof selector === 'string' ? $(selector) : selector;

    if ($element.length === 0) {
        console.warn('Datepicker element not found:', selector);
        return;
    }

    $element.datepicker(config);
}

/**
 * Initialize datepickers on all elements matching selector
 * @param {string} selector - CSS selector
 * @param {Object} options - Additional datepicker options
 */
export function initDatepickers(selector, options = {}) {
    if (typeof window.$ === 'undefined') return;

    $(selector).each(function() {
        initDatepicker($(this), options);
    });
}

/**
 * Initialize datepicker with custom year range
 * @param {string|jQuery} selector - Element selector or jQuery object
 * @param {string} yearRange - Year range (e.g., "-50:+50")
 * @param {Object} additionalOptions - Additional options
 */
export function initDatepickerWithYearRange(selector, yearRange = "-100:+0", additionalOptions = {}) {
    initDatepicker(selector, {
        ...additionalOptions,
        yearRange: yearRange
    });
}

/**
 * Auto-initialize datepickers with data attributes
 * Looks for elements with data-datepicker="true" or class "datepicker"
 */
export function autoInitDatepickers() {
    if (typeof window.$ === 'undefined') return;

    // Initialize elements with data-datepicker attribute
    $('[data-datepicker="true"]').each(function() {
        const $el = $(this);
        const yearRange = $el.data('year-range') || defaultConfig.yearRange;
        const dateFormat = $el.data('date-format') || defaultConfig.dateFormat;
        
        initDatepicker($el, {
            yearRange: yearRange,
            dateFormat: dateFormat
        });
    });

    // Initialize elements with .datepicker class (backward compatibility)
    $('.datepicker').each(function() {
        initDatepicker($(this));
    });
}

