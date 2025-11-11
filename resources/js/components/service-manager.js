/**
 * Service Manager Component
 * Handles service selection and addition
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { getSelectedCheckboxes } from '../utils/form-helper';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Service manager may not work.');
}

/**
 * Initialize service manager
 * @param {Object} options - Configuration options
 */
export function initServiceManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        modalSelector: '#servicesModal',
        storeRoute: options.storeRoute || '/orders',
        deleteRoute: options.deleteRoute || '/orders',
        entityId: options.entityId || null,
        ...options
    };

    // Handle add services
    $(document).on('click', config.modalSelector + ' .btn-submit', function() {
        const orderId = $('#orderID').val() || config.entityId;
        const serviceIds = getSelectedCheckboxes(config.modalSelector, '.serviceSelection:checkbox');

        if (serviceIds.length === 0) {
            alert('Please select at least one service');
            return;
        }

        ajaxRequest({
            url: config.storeRoute + '/' + orderId + '/service',
            method: 'POST',
            data: {
                order_id: orderId,
                service_id: serviceIds
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

    // Handle delete service
    $(document).on('click', '.deleteService', function(e) {
        e.preventDefault();

        const $button = $(this);
        const serviceId = $button.data('orderserviceid');
        const confirmMsg = $button.data('confirm') || 'Delete?';

        if (!window.confirm(confirmMsg)) {
            return;
        }

        ajaxRequest({
            url: config.deleteRoute + '/' + serviceId + '/service/delete',
            method: 'POST',
            data: { orderserviceid: serviceId },
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

