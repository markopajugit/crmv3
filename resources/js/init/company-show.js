/**
 * Company Show Page Initializer
 * Coordinates all components for the company show page
 */

import { initKycManager } from '../components/kyc-manager';
import { initDocumentManager } from '../components/document-manager';
import { initRiskManager } from '../components/risk-manager';
import { initTaxResidencyManager } from '../components/tax-residency-manager';
import { initCompanyEditor } from '../components/company-editor';
import { initDatepicker, autoInitDatepickers } from '../utils/datepicker';
import { setupAjaxDefaults } from '../utils/ajax-helper';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Company show initializer may not work.');
}

/**
 * Initialize company show page
 * @param {Object} options - Configuration options
 */
export function initCompanyShow(options = {}) {
    if (typeof window.$ === 'undefined') return;

    // Setup AJAX defaults
    setupAjaxDefaults();

    // Get entity ID from data attribute or options
    const entityId = $('[data-entity-id]').first().data('entity-id') || 
                     $('#companyID').val() || 
                     options.entityId;

    // Initialize datepickers
    autoInitDatepickers();
    initDatepicker('#authorised_person_deadline');

    // Initialize document manager
    initDocumentManager({
        entityId: entityId,
        entityType: 'company'
    });

    // Initialize KYC manager
    initKycManager({
        storeRoute: options.kycStoreRoute || '/kyc',
        updateRoute: options.kycUpdateRoute || '/kyc/update',
        userSearchRoute: options.userSearchRoute || '/autocomplete/user'
    });

    // Initialize risk manager
    initRiskManager({
        entityId: entityId,
        updateRoute: options.riskUpdateRoute || '/company/risk/update'
    });

    // Initialize tax residency manager
    initTaxResidencyManager({
        entityId: entityId,
        entityType: 'company',
        updateRoute: options.taxResidencyUpdateRoute || '/entitycontact/update/0'
    });

    // Initialize company editor
    initCompanyEditor({
        entityId: entityId,
        updateRoute: options.updateRoute || '/companies'
    });

    // Initialize person/company search autocomplete
    initPersonCompanySearch();
}

/**
 * Initialize person/company search autocomplete
 */
function initPersonCompanySearch() {
    if (typeof window.$ === 'undefined') return;

    // Person search for main contact
    $('#searchPerson').on('keyup', function() {
        const query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: '/autocomplete/modal',
                method: 'GET',
                data: { s: query, category: 'persons' },
                success: function(data) {
                    $('#searchResultsPerson').fadeIn();
                    $('#searchResultsPerson').html(data);
                }
            });
        }
    });

    // Person/Company search for related entities
    $('#searchPerson2').on('keyup', function() {
        const query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: '/autocomplete/modal',
                method: 'GET',
                data: { s: query, category: 'all' },
                success: function(data) {
                    $('#searchResultsPerson2').fadeIn();
                    $('#searchResultsPerson2').html(data);
                }
            });
        }
    });

    // Handle person selection
    $(document).on('click', '#searchResultsPerson li', function() {
        const personId = $(this).data('id');
        $('#searchPerson').val($(this).text());
        $('#personID').val(personId);
        $('#searchResultsPerson').fadeOut();

        // Get emails for selected person
        $.ajax({
            url: '/entitycontact/get/',
            method: 'POST',
            data: { entity_id: personId, type: 'email', entity: 'person' },
            success: function(data) {
                $('#emails').html(data);
            }
        });
    });

    // Handle person/company selection for related entities
    $(document).on('click', '#searchResultsPerson2 li', function() {
        const entityId = $(this).data('id');
        const entityType = $(this).data('type');
        $('#searchPerson2').val($(this).text());

        if (entityType === 'companies') {
            $('#selectedCompanyRelation').val(entityId);
            $('#selectedPersonRelation').val('');
        } else if (entityType === 'persons') {
            $('#selectedPersonRelation').val(entityId);
            $('#selectedCompanyRelation').val('');
        }

        $('#searchResultsPerson2').fadeOut();
    });

    // Hide search results when clicking outside
    $(document).on('click', function(event) {
        const $target = $(event.target);
        if (!$target.closest('#searchResultsPerson').length && $('#searchResultsPerson').is(':visible')) {
            $('#searchResultsPerson').hide();
        }
        if (!$target.closest('#searchResultsPerson2').length && $('#searchResultsPerson2').is(':visible')) {
            $('#searchResultsPerson2').hide();
        }
    });
}

// Export to window for Blade access
if (typeof window !== 'undefined') {
    window.initCompanyShow = initCompanyShow;
}

