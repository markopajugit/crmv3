/**
 * Order Show Page Initializer
 * Coordinates all components for the order show page
 */

import { initDocumentManager } from '../components/document-manager';
import { initPaymentManager } from '../components/payment-manager';
import { initNoteManager } from '../components/note-manager';
import { initContactManager } from '../components/contact-manager';
import { initInvoiceManager } from '../components/invoice-manager';
import { initServiceManager } from '../components/service-manager';
import { initFileUpload } from '../components/file-upload';
import { initOrderEditor } from '../components/order-editor';
import { initDatepicker, autoInitDatepickers } from '../utils/datepicker';
import { setupAjaxDefaults } from '../utils/ajax-helper';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Order show initializer may not work.');
}

/**
 * Initialize order show page
 * @param {Object} options - Configuration options
 */
export function initOrderShow(options = {}) {
    if (typeof window.$ === 'undefined') return;

    // Setup AJAX defaults
    setupAjaxDefaults();

    // Get entity ID from data attribute or options
    const entityId = $('[data-entity-id]').first().data('entity-id') || 
                     $('#orderID').val() || 
                     options.entityId;

    const orderNumber = $('h1').text().match(/\d+/)?.[0] || '';

    // Initialize datepickers
    autoInitDatepickers();

    // Set person_id to 0 on page load
    $(document).ready(function() {
        $('#person_id').val(0);
    });

    // Initialize document manager
    initDocumentManager({
        entityId: entityId,
        entityType: 'order',
        deleteRoute: options.deleteRoute || '/file/delete/order'
    });

    // Initialize payment manager
    initPaymentManager({
        entityId: entityId,
        storeRoute: options.paymentStoreRoute || '/orders',
        updateRoute: options.paymentUpdateRoute || '/orders/payment/update'
    });

    // Initialize note manager
    initNoteManager({
        entityId: entityId,
        entityType: 'order',
        storeRoute: options.noteStoreRoute || '/notes/order',
        updateRoute: options.noteUpdateRoute || '/notes/update',
        deleteRoute: options.noteDeleteRoute || '/notes/delete'
    });

    // Initialize contact manager
    initContactManager({
        entityId: entityId,
        storeRoute: options.contactStoreRoute || '/order/contact',
        updateRoute: options.contactUpdateRoute || '/order/contact/update',
        deleteRoute: options.contactDeleteRoute || '/order/contact/delete'
    });

    // Initialize invoice manager
    initInvoiceManager({
        entityId: entityId,
        storeRoute: options.invoiceStoreRoute || '/invoices'
    });

    // Initialize service manager
    initServiceManager({
        entityId: entityId,
        storeRoute: options.serviceStoreRoute || '/orders',
        deleteRoute: options.serviceDeleteRoute || '/orders'
    });

    // Initialize file upload (Dropzone)
    initFileUpload({
        entityId: entityId,
        entityType: 'order',
        uploadRoute: options.uploadRoute || '/file/upload'
    });

    // Initialize order editor
    initOrderEditor({
        entityId: entityId,
        orderNumber: orderNumber,
        updateRoute: options.updateRoute || '/orders'
    });

    // Initialize user search for payments
    initUserSearch();
}

/**
 * Initialize user search autocomplete
 */
function initUserSearch() {
    if (typeof window.$ === 'undefined') return;

    $(document).on('click', function(event) {
        const $target = $(event.target);
        if (!$target.closest('#searchResultsUser').length && $('#searchResultsUser').is(':visible')) {
            $('#searchResultsUser').hide();
        }
    });

    $('#searchUser').on('keyup', function() {
        const query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: '/autocomplete/user',
                method: 'GET',
                data: { s: query, category: 'users' },
                success: function(data) {
                    $('#searchResultsUser').fadeIn();
                    $('#searchResultsUser').html(data);
                }
            });
        }
    });

    $(document).on('click', '#searchResultsUser li', function() {
        $('#searchUser').val($(this).text());
        $('#userID').val($(this).data('id'));
        $('#searchResultsUser').fadeOut();
    });
}

// Export to window for Blade access
if (typeof window !== 'undefined') {
    window.initOrderShow = initOrderShow;
}

