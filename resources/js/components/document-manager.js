/**
 * Document Manager Component
 * Handles document deletion, archive number management, and document counts
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Document manager may not work.');
}

/**
 * Initialize document manager
 * @param {Object} options - Configuration options
 */
export function initDocumentManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        deleteRoute: options.deleteRoute || '/file/delete',
        archiveGenerateRoute: options.archiveGenerateRoute || '/file/archivenr/generate',
        archiveUpdateRoute: options.archiveUpdateRoute || '/file/archivenr',
        entityId: options.entityId || null,
        entityType: options.entityType || 'order',
        ...options
    };

    // Update document counts on page load
    function updateDocumentCounts() {
        const documentsCount = $('.regularDocuments table tr').length;
        const voDocumentsCount = $('.VOdocuments table tr').length;

        if ($('#documentsCount').length) {
            $('#documentsCount').html('(' + documentsCount + ')');
        }
        if ($('#VOdocumentsCount').length) {
            $('#VOdocumentsCount').html('(' + voDocumentsCount + ')');
        }
    }

    // Hide delete button for invoice documents
    function hideInvoiceDocumentDeleteButtons() {
        $('.deleteDocument').each(function() {
            const filename = $(this).data('filename');
            if (filename && filename.startsWith('invoice-')) {
                $(this).hide();
            }
        });
    }

    // Initialize on page load
    $(document).ready(function() {
        updateDocumentCounts();
        hideInvoiceDocumentDeleteButtons();
    });

    // Handle document deletion
    $(document).on('click', '.deleteDocument', function(e) {
        e.preventDefault();

        const $button = $(this);
        const fileName = $button.data('filename');
        const confirmMsg = $button.data('confirm') || 'Delete Document?';

        if (!window.confirm(confirmMsg)) {
            return;
        }

        const deleteUrl = config.deleteRoute + '/' + config.entityType + '/' + config.entityId + '/' + fileName;

        ajaxRequest({
            url: deleteUrl,
            method: 'DELETE',
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle archive number generation
    $(document).on('click', '.generateArchiveNumber', function() {
        const fileId = $(this).data('fileid');

        ajaxRequest({
            url: config.archiveGenerateRoute + '/' + fileId,
            method: 'POST',
            data: { generate: true },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle archive number editing
    $(document).on('click', '.editArchiveNumber', function() {
        const $button = $(this);
        const fileId = $button.data('fileid');
        const currentArchiveNumber = $('#orderArchiveNumber-' + fileId).html();

        $button.hide();
        $button.siblings('.saveArchiveNumber').show();

        $('#orderArchiveNumber-' + fileId).html(
            '<input type="text" id="updatedArchiveNumber" name="updatedArchiveNumber" value="' + currentArchiveNumber + '">'
        );
    });

    // Handle archive number saving
    $(document).on('click', '.saveArchiveNumber', function() {
        const $button = $(this);
        const fileId = $button.data('fileid');
        const updatedArchiveNumber = $('#updatedArchiveNumber').val();

        ajaxRequest({
            url: config.archiveUpdateRoute + '/' + fileId,
            method: 'POST',
            data: { archive_nr: updatedArchiveNumber },
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

