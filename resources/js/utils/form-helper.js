/**
 * Form Helper Utility
 * Provides form serialization and validation helpers
 */

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Form helper may not work.');
}

/**
 * Serialize form data to object
 * @param {string|jQuery} formSelector - Form selector or jQuery object
 * @returns {Object} Serialized form data
 */
export function serializeForm(formSelector) {
    if (typeof window.$ === 'undefined') return {};

    const $form = typeof formSelector === 'string' ? $(formSelector) : formSelector;
    
    if ($form.length === 0) {
        console.warn('Form not found:', formSelector);
        return {};
    }

    const formData = {};
    
    // Get all input, select, textarea values
    $form.find('input, select, textarea').each(function() {
        const $field = $(this);
        const name = $field.attr('name');
        const type = $field.attr('type');
        
        if (!name) return;

        if (type === 'checkbox') {
            if ($field.is(':checked')) {
                if (formData[name]) {
                    // Handle multiple checkboxes with same name
                    if (Array.isArray(formData[name])) {
                        formData[name].push($field.val());
                    } else {
                        formData[name] = [formData[name], $field.val()];
                    }
                } else {
                    formData[name] = $field.val();
                }
            }
        } else if (type === 'radio') {
            if ($field.is(':checked')) {
                formData[name] = $field.val();
            }
        } else {
            formData[name] = $field.val();
        }
    });

    return formData;
}

/**
 * Get form data as FormData object (for file uploads)
 * @param {string|jQuery} formSelector - Form selector or jQuery object
 * @returns {FormData} FormData object
 */
export function getFormData(formSelector) {
    const $form = typeof formSelector === 'string' ? $(formSelector) : formSelector;
    
    if ($form.length === 0) {
        console.warn('Form not found:', formSelector);
        return new FormData();
    }

    return new FormData($form[0]);
}

/**
 * Clear form fields
 * @param {string|jQuery} formSelector - Form selector or jQuery object
 */
export function clearForm(formSelector) {
    if (typeof window.$ === 'undefined') return;

    const $form = typeof formSelector === 'string' ? $(formSelector) : formSelector;
    
    if ($form.length === 0) {
        console.warn('Form not found:', formSelector);
        return;
    }

    $form[0].reset();
}

/**
 * Validate required fields in a form
 * @param {string|jQuery} formSelector - Form selector or jQuery object
 * @returns {Object} Validation result with isValid and errors
 */
export function validateForm(formSelector) {
    if (typeof window.$ === 'undefined') {
        return { isValid: false, errors: ['jQuery is not loaded'] };
    }

    const $form = typeof formSelector === 'string' ? $(formSelector) : formSelector;
    const errors = [];

    if ($form.length === 0) {
        return { isValid: false, errors: ['Form not found'] };
    }

    $form.find('[required]').each(function() {
        const $field = $(this);
        const value = $field.val();
        
        if (!value || value.trim() === '') {
            const fieldName = $field.attr('name') || $field.attr('id') || 'field';
            errors.push(`${fieldName} is required`);
        }
    });

    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

/**
 * Get checkbox values as array
 * @param {string} name - Checkbox name attribute
 * @returns {Array} Array of checked values
 */
export function getCheckboxValues(name) {
    if (typeof window.$ === 'undefined') return [];

    const values = [];
    $(`input[name="${name}"]:checked`).each(function() {
        values.push($(this).val());
    });
    return values;
}

/**
 * Get selected checkbox values from a container
 * @param {string|jQuery} containerSelector - Container selector
 * @param {string} checkboxSelector - Checkbox selector (default: 'input[type="checkbox"]')
 * @returns {Array} Array of checked values
 */
export function getSelectedCheckboxes(containerSelector, checkboxSelector = 'input[type="checkbox"]') {
    if (typeof window.$ === 'undefined') return [];

    const $container = typeof containerSelector === 'string' ? $(containerSelector) : containerSelector;
    const values = [];

    $container.find(checkboxSelector + ':checked').each(function() {
        values.push($(this).val());
    });

    return values;
}

