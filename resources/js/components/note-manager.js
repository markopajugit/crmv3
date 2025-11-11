/**
 * Note Manager Component
 * Handles note creation, editing, and deletion
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Note manager may not work.');
}

/**
 * Initialize note manager
 * @param {Object} options - Configuration options
 */
export function initNoteManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        addFormSelector: '#addNote',
        storeRoute: options.storeRoute || '/notes',
        updateRoute: options.updateRoute || '/notes/update',
        deleteRoute: options.deleteRoute || '/notes/delete',
        entityType: options.entityType || 'order',
        entityId: options.entityId || null,
        ...options
    };

    // Handle add note
    $(document).on('click', config.addFormSelector + ' .btn-submit', function() {
        const orderId = $('#orderID').val() || config.entityId;
        const content = $('#noteContent').val();

        ajaxRequest({
            url: config.storeRoute + '/' + config.entityType + '/' + orderId,
            method: 'POST',
            data: {
                order_id: orderId,
                content: content
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

    // Handle delete note
    $(document).on('click', '.note .fa-trash', function(e) {
        e.preventDefault();

        const $button = $(this);
        const noteId = $button.data('noteid');
        const confirmMsg = $button.data('confirm') || 'Remove Note?';

        if (!window.confirm(confirmMsg)) {
            return;
        }

        const orderId = $('#orderID').val() || config.entityId;

        ajaxRequest({
            url: config.deleteRoute + '/' + noteId,
            method: 'POST',
            data: { order_id: orderId },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle edit note (inline editing)
    $(document).on('click', '.note .fa-pen-to-square', function(e) {
        e.preventDefault();

        const $button = $(this);
        const content = $button.siblings('.noteContent').data('content');

        $button.siblings('.noteContent').html(
            '<textarea id="noteContentNew" name="noteContent" rows="4" cols="50">' + content + '</textarea>'
        );

        $button.hide();
        $button.siblings('.fa-check').show();
    });

    // Handle save edited note
    $(document).on('click', '.note .fa-check', function(e) {
        e.preventDefault();

        const $button = $(this);
        const noteId = $button.data('noteid');
        const content = $('#noteContentNew').val();
        const orderId = $('#orderID').val() || config.entityId;

        ajaxRequest({
            url: config.updateRoute + '/' + noteId,
            method: 'POST',
            data: {
                order_id: orderId,
                content: content
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

