/**
 * Tax Residency Manager Component
 * Handles tax residency CRUD operations
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Tax residency manager may not work.');
}

/**
 * Initialize tax residency manager
 * @param {Object} options - Configuration options
 */
export function initTaxResidencyManager(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        updateRoute: options.updateRoute || '/entitycontact/update/0',
        deleteRoute: options.deleteRoute || '/taxresidency',
        entityId: options.entityId || null,
        entityType: options.entityType || 'company',
        ...options
    };

    // Handle edit tax residency
    $(document).on('click', '#taxResidencyRow .fa-pen-to-square', function() {
        const $button = $(this);
        const taxResidencyValue = $button.parent().siblings('.taxResidency').children('.taxResidencyVal').html().trim();

        $button.hide();
        $button.siblings('.fa-check').show();

        // Replace with dropdown (simplified - in real implementation, you'd load full country list)
        $button.parent().siblings('.taxResidency').html(`
            <tr>
                <td style="border-top: 0px!important;padding:0;">
                    <select id="taxResidencyDropdown">
                        <option value="">country</option>
                        <!-- Country options would be loaded here -->
                    </select>
                </td>
            </tr>
        `);

        $('#taxResidencyDropdown').val(taxResidencyValue);
    });

    // Handle save tax residency
    $(document).on('click', '#taxResidencyRow .fa-check', function() {
        const updatedTaxResidencyValue = $('#taxResidencyDropdown').val();

        ajaxRequest({
            url: config.updateRoute,
            method: 'POST',
            data: {
                value: updatedTaxResidencyValue,
                company_id: config.entityId,
                type: 'taxResidency',
                entity: config.entityType
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

    // Handle delete tax residency (for person show page)
    $(document).on('click', '.delete-tax-residency', function() {
        if (!window.confirm('Are you sure you want to delete this tax residency?')) {
            return;
        }

        const taxResidencyId = $(this).data('id');

        ajaxRequest({
            url: config.deleteRoute + '/' + taxResidencyId,
            method: 'DELETE',
            reloadOnSuccess: true,
            success: function(data) {
                if (data.success) {
                    // Success handled by reload
                } else {
                    alert('Error deleting tax residency');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    // Handle edit tax residency (for person show page)
    $(document).on('click', '.edit-tax-residency', function() {
        const $button = $(this);
        const container = $button.closest('.tax-residency-item');
        const taxResidencyId = $button.data('id');
        const country = container.find('.country').text().trim();
        const validFrom = container.find('.valid-from').text().trim();
        const validTo = container.find('.valid-to').text().trim();
        const isPrimary = container.find('.primary-badge').length > 0;
        const notes = container.find('.notes').text().trim();

        // Replace with edit form
        container.find('.tax-residency-content').html(`
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 2px;">
                        <select class="edit-country-dropdown" style="width: 100%;">
                            <option value="">Select country</option>
                            <!-- Country options would be loaded here -->
                        </select>
                        <div style="display: flex; gap: 5px; margin-bottom: 5px;">
                            <input type="text" class="edit-valid-from" placeholder="Valid from (dd.mm.yyyy)" style="flex: 1;" value="${validFrom}">
                            <input type="text" class="edit-valid-to" placeholder="Valid to (dd.mm.yyyy)" style="flex: 1;" value="${validTo}">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label style="font-size: 12px;">
                                <input type="checkbox" class="edit-primary" style="margin-right: 5px;" ${isPrimary ? 'checked' : ''}>
                                Set as primary tax residency
                            </label>
                        </div>
                        <textarea class="edit-notes" placeholder="Notes (optional)" style="width: 100%; height: 50px; resize: vertical;">${notes}</textarea>
                    </td>
                    <td style="border: none; padding: 2px; text-align: center; width: 80px; vertical-align: top;">
                        <i class="fa-solid fa-check save-edit-tax-residency" data-id="${taxResidencyId}" style="cursor: pointer; color: #28a745; margin-right: 8px;"></i>
                        <i class="fa-solid fa-times cancel-edit-tax-residency" style="cursor: pointer; color: #dc3545;"></i>
                    </td>
                </tr>
            </table>
        `);

        // Set current country value
        container.find('.edit-country-dropdown').val(country);

        // Initialize date pickers
        initDatepicker(container.find('.edit-valid-from, .edit-valid-to'), {
            yearRange: "-50:+50"
        });
    });

    // Handle save edit tax residency
    $(document).on('click', '.save-edit-tax-residency', function() {
        const taxResidencyId = $(this).data('id');
        const container = $(this).closest('.tax-residency-item');
        const country = container.find('.edit-country-dropdown').val();
        const validFrom = container.find('.edit-valid-from').val();
        const validTo = container.find('.edit-valid-to').val();
        const isPrimary = container.find('.edit-primary').is(':checked');
        const notes = container.find('.edit-notes').val();

        if (!country) {
            alert('Please select a country');
            return;
        }

        ajaxRequest({
            url: config.deleteRoute + '/' + taxResidencyId,
            method: 'PUT',
            data: {
                country: country,
                valid_from: validFrom,
                valid_to: validTo,
                is_primary: isPrimary,
                notes: notes
            },
            reloadOnSuccess: true,
            success: function(data) {
                if (data.success) {
                    // Success handled by reload
                } else {
                    alert('Error updating tax residency');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    // Handle cancel edit tax residency
    $(document).on('click', '.cancel-edit-tax-residency', function() {
        window.location.reload();
    });
}

