/**
 * Invoice Manager Component
 * Handles invoice creation, VAT calculations, and invoice-related document handling
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Invoice manager may not work.');
}

/**
 * Calculate VAT and update services sum display
 */
function updateServicesSumWithVat() {
    const $servicesSum = $('#servicesSum');
    const $invoiceVat = $('#invoiceVat');

    if ($servicesSum.length === 0 || $invoiceVat.length === 0) {
        return;
    }

    const servicesSum = parseFloat($servicesSum.html()) || 0;
    const invoiceVat = parseFloat($invoiceVat.html()) || 0;

    let servicesSumWithVat = servicesSum;

    if (invoiceVat === 20) {
        servicesSumWithVat = Math.round(servicesSum * 1.2 * 100) / 100;
        $servicesSum.html(servicesSumWithVat + ' (with VAT)');
    } else if (invoiceVat === 22) {
        servicesSumWithVat = Math.round(servicesSum * 1.22 * 100) / 100;
        $servicesSum.html(servicesSumWithVat + ' (with VAT)');
    } else if (invoiceVat === 0) {
        servicesSumWithVat = Math.round(servicesSum * 100) / 100;
        $servicesSum.html(servicesSumWithVat + ' (with VAT)');
    }
}

/**
 * Initialize invoice manager
 * @param {Object} options - Configuration options
 */
export function initInvoiceManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        createProformaFormSelector: '#createInvoice',
        createInvoiceFormSelector: '#invoiceModal',
        storeRoute: options.storeRoute || '/invoices',
        entityId: options.entityId || null,
        ...options
    };

    // Initialize datepickers
    initDatepicker('#datepicker');
    initDatepicker('#paymentdatepicker');
    initDatepicker('#invoice_date_edit');
    initDatepicker('#invoice_payment_date_edit');

    // Update services sum with VAT on page load
    $(document).ready(function() {
        updateServicesSumWithVat();
    });

    // Handle create proforma
    $(document).on('click', config.createProformaFormSelector + ' .btn-submit', function() {
        const date = $('#datepicker').val();
        const paymentDate = $('#paymentdatepicker').val();
        const vat = $('.vatselection:checked').val();
        const orderId = $('#orderID').val() || config.entityId;
        const vatComment = $('#vat_comment').val();

        ajaxRequest({
            url: config.storeRoute,
            method: 'POST',
            data: {
                is_proforma: 1,
                issue_date: date,
                payment_date: paymentDate,
                vat: vat,
                order_id: orderId,
                vat_comment: vatComment
            },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.message || data.error);
                }
            },
            error: function(xhr) {
                printErrorMsg(xhr.responseJSON?.message || 'Error creating proforma');
            }
        });
    });

    // Handle create invoice
    $(document).on('click', config.createInvoiceFormSelector + ' .btn-submit', function() {
        const payerName = $('#invoicePayerName').val();
        const registryCode = $('#reg_code').val();
        const vat = $('.vatselection:checked').val();
        const orderId = $('#orderID').val() || config.entityId;
        const address = $('#address').val();
        const issueDate = $('#invoice_date_edit').val();
        const paymentDate = $('#invoice_payment_date_edit').val();
        const vatNo = $('#vat_no').val();
        const street = $('#invoiceStreet').val();
        const city = $('#invoiceCity').val();
        const zip = $('#invoiceZip').val();
        const country = $('#invoiceCountry').val();
        const invoiceCompany = $('input[name="invoicecompany"]:checked').val();

        ajaxRequest({
            url: config.storeRoute,
            method: 'POST',
            data: {
                is_proforma: 0,
                payer_name: payerName,
                registry_code: registryCode,
                vat: vat,
                vat_no: vatNo,
                order_id: orderId,
                address: address,
                issue_date: issueDate,
                payment_date: paymentDate,
                street: street,
                city: city,
                zip: zip,
                country: country,
                invoicecompany: invoiceCompany
            },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.message || data.error);
                }
            },
            error: function(xhr) {
                printErrorMsg(xhr.responseJSON?.message || 'Error creating invoice');
            }
        });
    });

    // Show invoice modal based on payment status
    function showInvoiceModal() {
        const paymentStatus = $('#payment_status option:selected').text();
        if (paymentStatus === 'Paid' && !$('#invoice_exists').length) {
            // Modal would be shown here if needed
        }
    }
}

