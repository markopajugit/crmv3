/**
 * Payment Manager Component
 * Handles payment creation and editing
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Payment manager may not work.');
}

/**
 * Initialize payment manager
 * @param {Object} options - Configuration options
 */
export function initPaymentManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        addFormSelector: '#addNewPayment',
        editFormSelector: '#editPayment',
        storeRoute: options.storeRoute || '/orders',
        updateRoute: options.updateRoute || '/orders/payment/update',
        entityId: options.entityId || null,
        ...options
    };

    // Initialize datepickers
    initDatepicker('#addNewPayment #paiddate');
    initDatepicker('#editPayment #editpaiddate');

    // Handle add payment
    $(document).on('click', config.addFormSelector + ' .btn-submit', function() {
        const type = $('#paidtype').val();
        const sum = $('#paidsum').val();
        const details = $('#paiddetails').val();
        const date = $('#paiddate').val();

        ajaxRequest({
            url: config.storeRoute + '/' + config.entityId + '/payment',
            method: 'POST',
            data: {
                type: type,
                sum: sum,
                details: details,
                paid_date: date
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

    // Handle edit payment
    $(document).on('click', config.editFormSelector + ' .btn-submit', function() {
        const type = $(config.editFormSelector + ' #paidtype').val();
        const sum = $(config.editFormSelector + ' #paidsum').val();
        const details = $(config.editFormSelector + ' #paiddetails').val();
        const date = $(config.editFormSelector + ' #editpaiddate').val();
        const paymentId = $(config.editFormSelector + ' #paymentID').val();

        ajaxRequest({
            url: config.updateRoute + '/' + paymentId,
            method: 'POST',
            data: {
                type: type,
                sum: sum,
                details: details,
                paid_date: date
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

