/**
 * KYC Manager Component
 * Handles KYC modal operations (add/edit) and user search autocomplete
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. KYC manager may not work.');
}

/**
 * Initialize user search autocomplete
 * @param {string} inputSelector - Input selector
 * @param {string} resultsSelector - Results container selector
 * @param {string} hiddenInputSelector - Hidden input for selected user ID
 * @param {string} route - Autocomplete route
 */
function initUserSearch(inputSelector, resultsSelector, hiddenInputSelector, route) {
    if (typeof window.$ === 'undefined') return;

    const $input = $(inputSelector);
    const $results = $(resultsSelector);
    const $hiddenInput = $(hiddenInputSelector);

    if ($input.length === 0) return;

    // Hide results when clicking outside
    $(document).on('click', function(event) {
        const $target = $(event.target);
        if (!$target.closest(resultsSelector).length && $results.is(':visible')) {
            $results.hide();
        }
    });

    // Handle search input
    $input.on('keyup', function() {
        const query = $(this).val();
        if (query !== '') {
            ajaxRequest({
                url: route,
                method: 'GET',
                data: { s: query, category: 'users' },
                success: function(data) {
                    if (data && data.trim() !== '' && data.trim() !== '<ul></ul>') {
                        $results.fadeIn();
                        $results.html(data);
                    } else {
                        $results.hide();
                    }
                },
                error: handleAjaxError
            });
        } else {
            $results.hide();
        }
    });

    // Handle result selection
    $(document).on('click', resultsSelector + ' li', function() {
        const userId = $(this).data('id');
        $input.val($(this).text());
        $hiddenInput.val(userId);
        $results.fadeOut();
    });
}

/**
 * Initialize KYC manager
 * @param {Object} options - Configuration options
 */
export function initKycManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        addModalId: '#addKycModal',
        editModalId: '#editKycModal',
        addFormId: '#kycForm',
        editFormId: '#editKycForm',
        saveButtonId: '#saveKycRecord',
        updateButtonId: '#updateKycRecord',
        storeRoute: options.storeRoute || '/kyc',
        updateRoute: options.updateRoute || '/kyc/update',
        userSearchRoute: options.userSearchRoute || '/autocomplete/user',
        ...options
    };

    // Initialize datepickers
    initDatepicker('#kycStartDate');
    initDatepicker('#kycEndDate');
    initDatepicker('#editKycStartDate');
    initDatepicker('#editKycEndDate');

    // Initialize user search for add modal
    initUserSearch(
        '#kycResponsibleUser',
        '#kycResponsibleUserResults',
        '#kycResponsibleUserId',
        config.userSearchRoute
    );

    // Initialize user search for edit modal
    initUserSearch(
        '#editKycResponsibleUser',
        '#editKycResponsibleUserResults',
        '#editKycResponsibleUserId',
        config.userSearchRoute
    );

    // Handle save KYC record
    $(document).on('click', config.saveButtonId, function() {
        const $form = $(config.addFormId);
        const entityId = $('#kycCompanyId').val() || $('#kycPersonId').val();
        const kycableType = $('#kycableType').val();

        const formData = {
            kycable_type: kycableType,
            kycable_id: entityId,
            responsible_user_id: $('#kycResponsibleUserId').val(),
            start_date: $('#kycStartDate').val(),
            end_date: $('#kycEndDate').val(),
            risk: $('#kycRisk').val(),
            documents: $('#kycDocuments').val(),
            comments: $('#kycComments').val()
        };

        ajaxRequest({
            url: config.storeRoute,
            method: 'POST',
            data: formData,
            reloadOnSuccess: true,
            success: function(data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
            },
            error: handleAjaxError
        });
    });

    // Handle update KYC record
    $(document).on('click', config.updateButtonId, function() {
        const kycId = $('#editKycId').val();

        const formData = {
            responsible_user_id: $('#editKycResponsibleUserId').val(),
            start_date: $('#editKycStartDate').val(),
            end_date: $('#editKycEndDate').val(),
            risk: $('#editKycRisk').val(),
            documents: $('#editKycDocuments').val(),
            comments: $('#editKycComments').val()
        };

        ajaxRequest({
            url: config.updateRoute + '/' + kycId,
            method: 'PUT',
            data: formData,
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

