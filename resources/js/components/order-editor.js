/**
 * Order Editor Component
 * Handles order name inline editing
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Order editor may not work.');
}

/**
 * Initialize order editor
 * @param {Object} options - Configuration options
 */
export function initOrderEditor(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        updateRoute: options.updateRoute || '/orders',
        entityId: options.entityId || null,
        orderNumber: options.orderNumber || '',
        ...options
    };

    // Handle inline name editing
    $(document).on('click', 'h1 i.fa-pen-to-square', function() {
        const $button = $(this);
        const $h1 = $button.parent();
        const currentName = $h1.find('input[name="name"]').val() || $h1.text().replace(config.orderNumber, '').trim();

        $h1.html(`
            <form action="${config.updateRoute}/${config.entityId}" method="POST" style="display: inline;">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="PUT">
                <i class="fa-solid fa-file"></i>${config.orderNumber}
                <input type="text" name="name" placeholder="Name" value="${currentName}" style="font-size:24px;">
                <button type="button" onClick="window.location.reload();" style="margin-right: 5px;" class="cancelEdit btn">Cancel</button>
                <button type="submit" class="saveEdit btn">Save</button>
            </form>
        `);

        $('h5').hide();
    });
}

