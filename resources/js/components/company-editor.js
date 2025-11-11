/**
 * Company Editor Component
 * Handles company name inline editing and company-related entity management
 */

import { ajaxRequest } from '../utils/ajax-helper';
import { printErrorMsg, handleAjaxError } from '../utils/error-handler';
import { initDatepicker } from '../utils/datepicker';

if (typeof window.$ === 'undefined') {
    console.warn('jQuery is not loaded. Company editor may not work.');
}

/**
 * Initialize company editor
 * @param {Object} options - Configuration options
 */
export function initCompanyEditor(options = {}) {
    if (typeof window.$ === 'undefined') return;

    const config = {
        updateRoute: options.updateRoute || '/companies',
        entityId: options.entityId || null,
        ...options
    };

    // Handle inline name editing
    $(document).on('click', 'h1 i.fa-pen-to-square', function() {
        const $button = $(this);
        const $h1 = $button.parent();
        const currentName = $h1.clone().children().remove().end().text().trim();
        const companyNumber = $('h5').text().trim();

        $h1.html(`
            <form action="${config.updateRoute}/${config.entityId}" method="POST" style="display: inline;">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="PUT">
                <i class="fa-solid fa-building"></i>
                <input type="text" name="name" value="${currentName}" style="font-size:26px;">
                <button type="button" class="cancelEdit btn" style="margin-right: 5px;">Cancel</button>
                <button type="submit" class="saveEdit btn">Save</button>
            </form>
        `);

        $('h5').hide();
    });

    // Handle cancel edit
    $(document).on('click', '.cancelEdit', function() {
        window.location.reload();
    });

    // Initialize datepicker for authorised person deadline
    initDatepicker('#authorised_person_deadline');

    // Handle add related person/company
    $(document).on('click', '#relatedPerson .btn-submit', function() {
        const companyId = $('#companyID').val() || config.entityId;
        const personId = $('#personID').val();
        const authorisedPersonDeadline = $('#authorised_person_deadline').val();

        let type = 'person';
        let entityId = $('#selectedPersonRelation').val();

        if ($('#selectedCompanyRelation').val()) {
            type = 'company';
            entityId = $('#selectedCompanyRelation').val();
        }

        const relationArray = [];
        $('.personCompanyRelation:checked').each(function() {
            relationArray.push($(this).val());
        });

        if ($('#otherRelation').val()) {
            relationArray.push($('#otherRelation').val());
        }

        const relation = relationArray.toString();

        ajaxRequest({
            url: '/companies/' + companyId + '/client',
            method: 'POST',
            data: {
                company_id: companyId,
                entity_id: entityId,
                relation: relation,
                type: type,
                contact_deadline: authorisedPersonDeadline
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

    // Handle add main contact
    $(document).on('click', '#addMainContact .btn-submit', function() {
        const companyId = config.entityId;
        const personId = $('#personID').val();
        const chosenEmail = $('input[name=emails]:checked', '#emails').val();

        ajaxRequest({
            url: '/companies/' + companyId + '/client',
            method: 'POST',
            data: {
                company_id: companyId,
                entity_id: personId,
                relation: 'Main Contact',
                selected_email: chosenEmail,
                type: 'person'
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

    // Handle add order
    $(document).on('click', '#addOrderModal .btn-submit', function() {
        const companyId = $('#companyID').val() || config.entityId;
        const userId = $('#userID').val();
        const name = $('#nameID').val();

        ajaxRequest({
            url: '/orders',
            method: 'POST',
            data: {
                company_id: companyId,
                responsible_user_id: userId,
                name: name,
                description: '',
                notes: ''
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

    // Handle edit notes
    $(document).on('click', '.editNotes', function() {
        $(this).html('<i class="fa-solid fa-pen-to-square"></i>Save');
        $(this).hide();
        $('.saveNotes').show();

        const currentNotes = $('.panel-notes .panel-body').html();
        $('.panel-notes .panel-body').html(
            '<textarea cols="60" rows="5" id="notes" name="notes">' + $.trim(currentNotes) + '</textarea>'
        );
    });

    $(document).on('click', '.panel-notes .panel-heading__button .saveNotes', function() {
        const notesVal = $('#notes').val();
        const companyId = $('#companyID').val() || config.entityId;

        ajaxRequest({
            url: config.updateRoute + '/' + companyId,
            method: 'PUT',
            data: {
                company_id: companyId,
                notes: notesVal
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

// Export to window for global access
if (typeof window !== 'undefined') {
    window.CompanyEditor = { init: initCompanyEditor };
}

