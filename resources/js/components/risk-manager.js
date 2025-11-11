/**
 * Risk Manager Component
 * Handles risk level editing and risk history display
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Risk manager may not work.');
}

/**
 * Initialize risk manager
 * @param {Object} options - Configuration options
 */
export function initRiskManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        updateRoute: options.updateRoute || '/company/risk/update',
        entityId: options.entityId || null,
        ...options
    };

    // Handle edit risk
    $(document).on('click', '#riskEdit .fa-pen-to-square', function() {
        const $button = $(this);
        const companyRiskValue = $button.parent().siblings('.currentCompanyRisk').children('span').html();

        $button.parent().siblings('.currentCompanyRisk').children('span').html(`
            <select name="riskOptions" id="riskOptions">
                <option value="1">Low</option>
                <option value="2">Medium</option>
                <option value="3">High</option>
            </select>
        `);

        // Set current value
        if (companyRiskValue === 'Low') {
            $('#riskOptions').val('1');
        } else if (companyRiskValue === 'Medium') {
            $('#riskOptions').val('2');
        } else if (companyRiskValue === 'High') {
            $('#riskOptions').val('3');
        }

        $button.hide();
        $button.siblings('.fa-check').show();
    });

    // Handle save risk
    $(document).on('click', '#riskEdit .fa-check', function() {
        const risk = $('#riskOptions').val();

        ajaxRequest({
            url: config.updateRoute,
            method: 'POST',
            data: {
                company_id: config.entityId,
                risk_level: risk
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

    // Handle show risk history
    $(document).on('click', '#showRiskHistory', function() {
        $('.riskHistoryRows').show();
        $('.currentCompanyRisk').hide();
        $('#riskEdit .fa-pen-to-square').hide();
        $('#riskEdit .fa-check').hide();
        $(this).hide();
    });
}

