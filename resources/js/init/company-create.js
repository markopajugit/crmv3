/**
 * Company Create Page Initializer
 */

import { initDatepicker } from '../utils/datepicker';
import { printErrorMsg } from '../utils/error-handler';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Company create initializer may not work.');
}

/**
 * Initialize company create page
 */
export function initCompanyCreate() {
    if (typeof window.$ === 'undefined') return;

    // Initialize datepicker
    initDatepicker('#insertedRegistrationDate');

    // Handle save new company
    $('.saveNewCompany').on('click', function() {
        $('#name').val($('#insertedName').val());
        $('#registry_code').val($('#insertedRegistryCode').val());
        $('#registration_country').val($('#insertedRegistrationCountry').val());
        $('#vat').val($('#insertedVat').val());
        $('#registration_date').val($('#insertedRegistrationDate').val());
        $('#email').val($('#insertedEmail').val());
        $('#address_street').val($('#insertedAddressStreet').val());
        $('#address_city').val($('#insertedAddressCity').val());
        $('#address_zip').val($('#insertedAddressZip').val());
        $('#address_dropdown').val($('#insertedAddressDropdown').val());
        $('#notes').val($('#insertedNotes').val());

        if ($('input#generateNumber').is(':checked')) {
            $('#number').val(0);
        } else {
            $('#number').val($('#company_number').val());
        }

        $('#addNewCompany').submit();
    });

    // Handle generate number checkbox
    $('input#generateNumber').change(function() {
        if ($(this).is(':checked')) {
            $('#company_number_holder').hide();
        } else {
            $('#company_number_holder').show();
        }
    });
}

// Export to window for Blade access
if (typeof window !== 'undefined') {
    window.initCompanyCreate = initCompanyCreate;
}

