/**
 * Person Show Page Initializer
 * Coordinates all components for the person show page
 */

import { initKycManager } from '../components/kyc-manager';
import { initDocumentManager } from '../components/document-manager';
import { initTaxResidencyManager } from '../components/tax-residency-manager';
import { initPersonEditor } from '../components/person-editor';
import { initDatepicker, autoInitDatepickers } from '../utils/datepicker';
import { setupAjaxDefaults } from '../utils/ajax-helper';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Person show initializer may not work.');
}

/**
 * Initialize person show page
 * @param {Object} options - Configuration options
 */
export function initPersonShow(options = {}) {
    if (typeof window.$ === 'undefined') return;

    // Setup AJAX defaults
    setupAjaxDefaults();

    // Get entity ID from data attribute or options
    const entityId = $('[data-entity-id]').first().data('entity-id') || 
                     $('#personID').val() || 
                     options.entityId;

    // Initialize datepickers
    autoInitDatepickers();

    // Initialize document manager
    initDocumentManager({
        entityId: entityId,
        entityType: 'person'
    });

    // Initialize KYC manager
    initKycManager({
        storeRoute: options.kycStoreRoute || '/kyc',
        updateRoute: options.kycUpdateRoute || '/kyc/update',
        userSearchRoute: options.userSearchRoute || '/autocomplete/user'
    });

    // Initialize tax residency manager
    initTaxResidencyManager({
        entityId: entityId,
        entityType: 'person',
        updateRoute: options.taxResidencyUpdateRoute || '/taxresidency',
        deleteRoute: options.taxResidencyDeleteRoute || '/taxresidency'
    });

    // Initialize person editor
    initPersonEditor({
        entityId: entityId,
        updateRoute: options.updateRoute || '/persons'
    });

    // Handle delete person
    $(document).on('click', '#deletePerson', function() {
        if (!window.confirm('Are you sure you want to delete this person?')) {
            return;
        }

        const personId = $(this).data('personid');
        $.ajax({
            url: '/persons/' + personId,
            method: 'DELETE',
            success: function() {
                window.location.href = '/persons';
            },
            error: function(xhr) {
                alert('Error deleting person: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
}

// Export to window for Blade access
if (typeof window !== 'undefined') {
    window.initPersonShow = initPersonShow;
}

