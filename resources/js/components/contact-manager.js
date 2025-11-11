/**
 * Contact Manager Component
 * Handles contact person CRUD operations
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Contact manager may not work.');
}

/**
 * Initialize contact manager
 * @param {Object} options - Configuration options
 */
export function initContactManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        addFormSelector: '#addContactPerson',
        storeRoute: options.storeRoute || '/order/contact',
        updateRoute: options.updateRoute || '/order/contact/update',
        deleteRoute: options.deleteRoute || '/order/contact/delete',
        entityId: options.entityId || null,
        ...options
    };

    // Handle add contact person
    $(document).on('click', config.addFormSelector + ' .btn-submit', function() {
        const personName = $('#orderContactName').val();
        const personEmail = $('#orderContactEmail').val();
        const personId = $('#person_id').val();
        const createPerson = $('input#createPerson').is(':checked') ? 1 : 0;

        ajaxRequest({
            url: config.storeRoute + '/' + config.entityId,
            method: 'POST',
            data: {
                name: personName,
                email: personEmail,
                person_id: personId,
                createPerson: createPerson
            },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle delete contact person
    $(document).on('click', '.panel-contactpersons .fa-trash', function(e) {
        e.preventDefault();

        const $button = $(this);
        const personId = $button.data('personid');
        const confirmMsg = $button.data('confirm') || 'Remove Contact Person?';

        if (!window.confirm(confirmMsg)) {
            return;
        }

        ajaxRequest({
            url: config.deleteRoute + '/' + personId,
            method: 'POST',
            data: { order_id: config.entityId },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle edit contact person (inline editing)
    $(document).on('click', '.panel-contactpersons .fa-pen-to-square', function(e) {
        e.preventDefault();

        const $button = $(this);
        const name = $button.parent().siblings('.person-name').html();
        const email = $button.parent().siblings('.person-email').html();

        $button.parent().siblings('.person-name').html(
            '<input type="text" id="person-name-new" value="' + name + '">'
        );
        $button.parent().siblings('.person-email').html(
            '<input type="text" id="person-email-new" value="' + email + '">'
        );

        $button.hide();
        $button.siblings('.fa-check').show();
    });

    // Handle save edited contact person
    $(document).on('click', '.panel-contactpersons .fa-check', function(e) {
        e.preventDefault();

        const $button = $(this);
        const personId = $button.data('personid');
        const personName = $('#person-name-new').val();
        const personEmail = $('#person-email-new').val();

        ajaxRequest({
            url: config.updateRoute + '/' + personId,
            method: 'POST',
            data: {
                order_id: config.entityId,
                email: personEmail,
                name: personName
            },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });
}

