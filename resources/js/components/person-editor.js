/**
 * Person Editor Component
 * Handles person name and date of birth inline editing
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Person editor may not work.');
}

/**
 * Initialize person editor
 * @param {Object} options - Configuration options
 */
export function initPersonEditor(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        updateRoute: options.updateRoute || '/persons',
        entityId: options.entityId || null,
        ...options
    };

    // Store original content
    let originalH1 = '';
    let originalH5 = '';

    $(document).ready(function() {
        originalH1 = $('h1').html();
        originalH5 = $('h5').html();
    });

    // Handle inline name/DOB editing
    $(document).on('click', 'h1 i.fa-pen-to-square, h1 i', function() {
        const $button = $(this);
        const $h1 = $button.closest('h1');
        const currentName = $h1.clone().children().remove().end().text().trim();
        const currentDob = $('h5').text().trim();

        $('h5').empty();
        $h1.html(`
            <form action="${config.updateRoute}/${config.entityId}" method="POST" style="display: inline;">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="PUT">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="name" value="${currentName}" style="font-size:26px;"><br>
                <input type="text" id="date_of_birth" name="date_of_birth" value="${currentDob}" style="font-size:18px;">
                <button style="margin-right: 5px;" class="cancelEdit btn">Cancel</button>
                <button type="submit" class="saveEdit btn">Save</button>
            </form>
        `);

        // Initialize datepicker
        initDatepicker('#date_of_birth');
    });

    // Handle cancel edit
    $(document).on('click', 'h1 .btn.cancelEdit', function() {
        $('h1').html(originalH1);
        $('h5').html(originalH5);
    });
}

