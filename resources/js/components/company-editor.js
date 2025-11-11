/**
 * Company Editor Component
 * Handles all inline editing functionality for company show page
 */

// Import AJAX helper utilities
// Note: In a real ES6 module setup, this would be: import { ajaxRequest, handleAjaxResponse, updateFieldValue, showError, showSuccess, showLoading, hideLoading } from '../utils/ajax-helper';
// For now, we'll assume these are available globally or we'll define them inline

// Countries list (extracted from blade template)
const COUNTRIES = [
    'Afghanistan', 'Aland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla',
    'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan',
    'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan',
    'Bolivia', 'Bonaire, Sint Eustatius and Saba', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil',
    'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia',
    'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China',
    'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Congo, Democratic Republic of the Congo',
    'Cook Islands', 'Costa Rica', 'Cote D\'Ivoire', 'Croatia', 'Cuba', 'Curacao', 'Cyprus', 'Czech Republic',
    'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea',
    'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France',
    'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana',
    'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-Bissau',
    'Guyana', 'Haiti', 'Heard Island and Mcdonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong',
    'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Isle of Man', 'Israel',
    'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of',
    'Korea, Republic of', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon',
    'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao',
    'Macedonia, the Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta',
    'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of',
    'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar',
    'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua',
    'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau',
    'Palestinian Territory, Occupied', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn',
    'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Barthelemy',
    'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Martin', 'Saint Pierre and Miquelon',
    'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal',
    'Serbia', 'Serbia and Montenegro', 'Seychelles', 'Sierra Leone', 'Singapore', 'Sint Maarten', 'Slovakia', 'Slovenia',
    'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'South Sudan', 'Spain',
    'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic',
    'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Timor-Leste', 'Togo', 'Tokelau',
    'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'Uganda',
    'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay',
    'Uzbekistan', 'Vanuatu', 'Venezuela', 'Vietnam', 'Virgin Islands, British', 'Virgin Islands, U.s.', 'Wallis and Futuna',
    'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'
];

class CompanyEditor {
    constructor(companyId, updateUrl) {
        this.companyId = companyId;
        this.updateUrl = updateUrl;
        this.originalContent = {};
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            $(document).ready(() => this.initializeComponents());
        } else {
            this.initializeComponents();
        }
    }

    initializeComponents() {
        this.initCompanyNameEditor();
        this.initFieldEditors();
        this.initNotesEditor();
        this.initRiskEditor();
        this.initTaxResidencyEditor();
        this.initKycEditor();
        this.initActivityCodeEditors();
        this.initRelatedEntities();
    }

    /**
     * Initialize company name/number/registry code editor
     */
    initCompanyNameEditor() {
        const $h1 = $('h1');
        const $h5 = $('h5');
        
        // Store original content
        this.originalContent.h1 = $h1.html();
        this.originalContent.h5 = $h5.html();

        $h1.on('click', 'i.fa-pen-to-square', () => {
            const $editIcon = $h1.find('i.fa-pen-to-square');
            const currentNumber = $h1.data('company-number') || '';
            const currentName = $h1.data('company-name') || '';
            const currentRegistry = $h5.data('registry-code') || '';

            $h1.html(`
                <form class="company-name-form">
                    <i class="fa-solid fa-building"></i>
                    <input type="text" name="number" placeholder="Company number" value="${this.escapeHtml(currentNumber)}" style="font-size:24px;" class="form-control d-inline-block" style="width: auto;">
                    <input type="text" name="name" value="${this.escapeHtml(currentName)}" style="font-size:26px;" class="form-control d-inline-block" style="width: auto;">
                    <br>
                    <span style="font-size:18px;">Reg:</span>
                    <input type="text" name="registry_code" value="${this.escapeHtml(currentRegistry)}" style="font-size:18px;" class="form-control d-inline-block" style="width: auto;">
                    <button type="button" class="btn btn-secondary cancelEdit" style="margin-right: 5px;">Cancel</button>
                    <button type="submit" class="btn btn-primary saveEdit">Save</button>
                </form>
            `);
            $h5.hide();
        });

        $h1.on('click', '.btn.cancelEdit', () => {
            $h1.html(this.originalContent.h1);
            $h5.html(this.originalContent.h5);
        });

        $h1.on('submit', '.company-name-form', (e) => {
            e.preventDefault();
            const formData = {
                number: $h1.find('input[name="number"]').val(),
                name: $h1.find('input[name="name"]').val(),
                registry_code: $h1.find('input[name="registry_code"]').val()
            };

            this.updateCompanyField(formData, (data) => {
                $h1.html(`<i class="fa-solid fa-building"></i><i>${this.escapeHtml(data.number || '')}</i> ${this.escapeHtml(data.name || '')}<i class="fa-solid fa-pen-to-square" style="cursor: pointer;vertical-align: middle; margin-left: 10px;font-size: 20px;"></i>`);
                $h1.data('company-number', data.number);
                $h1.data('company-name', data.name);
                
                if (data.registry_code) {
                    $h5.html(`Reg: ${this.escapeHtml(data.registry_code)}`);
                    $h5.data('registry-code', data.registry_code);
                }
                $h5.show();
                this.originalContent.h1 = $h1.html();
                this.originalContent.h5 = $h5.html();
            });
        });
    }

    /**
     * Initialize generic field editors (text, date, select)
     */
    initFieldEditors() {
        // Registration date editor
        this.initDateFieldEditor('.editDate', '#dateRow', 'registration_date', 'Registration date:', 'updatedDate', 'saveDate');
        
        // VAT editor
        this.initTextFieldEditor('.editVat', '#vatRow', 'vat', 'VAT No:', 'updatedVat', 'saveVat');
        
        // Registration country editor
        this.initSelectFieldEditor('.editRegCountry', '#regCountryRow', 'registration_country', 'Registration country:', 'updatedRegCountry', 'saveRegCountry', COUNTRIES);
    }

    /**
     * Initialize date field editor
     */
    initDateFieldEditor(editSelector, rowSelector, fieldName, label, inputId, saveId) {
        $(editSelector).on('click', () => {
            $(editSelector).hide();
            const currentValue = $(rowSelector).data('current-value') || '';
            
            $(rowSelector).html(`
                <td><b>${label}</b></td>
                <td><input type="text" value="${this.escapeHtml(currentValue)}" id="${inputId}" class="form-control"></td>
                <td><i class="fa-solid fa-check" id="${saveId}" style="cursor: pointer;"></i></td>
            `);

            $(`#${inputId}`).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
        });

        $('.panel-details').on('click', `#${saveId}`, () => {
            const value = $(`#${inputId}`).val();
            const data = {};
            data[fieldName] = value;
            
            this.updateCompanyField(data, (responseData) => {
                const displayValue = responseData[fieldName] || '';
                $(rowSelector).html(`
                    <td><b>${label}</b></td>
                    <td>${this.escapeHtml(displayValue)}</td>
                    <td><i class="fa-solid fa-pen-to-square editDate" style="cursor: pointer;"></i></td>
                `);
                $(rowSelector).data('current-value', displayValue);
                this.initDateFieldEditor(editSelector, rowSelector, fieldName, label, inputId, saveId);
            });
        });
    }

    /**
     * Initialize text field editor
     */
    initTextFieldEditor(editSelector, rowSelector, fieldName, label, inputId, saveId) {
        $(editSelector).on('click', () => {
            $(editSelector).hide();
            const currentValue = $(rowSelector).data('current-value') || '';
            
            $(rowSelector).html(`
                <td><b>${label}</b></td>
                <td><input type="text" value="${this.escapeHtml(currentValue)}" id="${inputId}" class="form-control"></td>
                <td><i class="fa-solid fa-check" id="${saveId}" style="cursor: pointer;"></i></td>
            `);
        });

        $('.panel-details').on('click', `#${saveId}`, () => {
            const value = $(`#${inputId}`).val();
            const data = {};
            data[fieldName] = value;
            
            this.updateCompanyField(data, (responseData) => {
                const displayValue = responseData[fieldName] || '';
                $(rowSelector).html(`
                    <td><b>${label}</b></td>
                    <td>${this.escapeHtml(displayValue)}</td>
                    <td><i class="fa-solid fa-pen-to-square ${editSelector.replace('.', '')}" style="cursor: pointer;"></i></td>
                `);
                $(rowSelector).data('current-value', displayValue);
                this.initTextFieldEditor(editSelector, rowSelector, fieldName, label, inputId, saveId);
            });
        });
    }

    /**
     * Initialize select field editor
     */
    initSelectFieldEditor(editSelector, rowSelector, fieldName, label, selectId, saveId, options) {
        $(editSelector).on('click', () => {
            $(editSelector).hide();
            const currentValue = $(rowSelector).data('current-value') || '';
            
            let optionsHtml = '<option value="">Select country</option>';
            options.forEach(country => {
                const selected = currentValue === country ? 'selected' : '';
                optionsHtml += `<option value="${this.escapeHtml(country)}" ${selected}>${this.escapeHtml(country)}</option>`;
            });
            
            $(rowSelector).html(`
                <td><b>${label}</b></td>
                <td><select id="${selectId}" class="form-control">${optionsHtml}</select></td>
                <td><i class="fa-solid fa-check" id="${saveId}" style="cursor: pointer;"></i></td>
            `);
        });

        $('.panel-details').on('click', `#${saveId}`, () => {
            const value = $(`#${selectId}`).val();
            const data = {};
            data[fieldName] = value;
            
            this.updateCompanyField(data, (responseData) => {
                const displayValue = responseData[fieldName] || '';
                $(rowSelector).html(`
                    <td><b>${label}</b></td>
                    <td>${this.escapeHtml(displayValue)}</td>
                    <td><i class="fa-solid fa-pen-to-square ${editSelector.replace('.', '')}" style="cursor: pointer;"></i></td>
                `);
                $(rowSelector).data('current-value', displayValue);
                this.initSelectFieldEditor(editSelector, rowSelector, fieldName, label, selectId, saveId, options);
            });
        });
    }

    /**
     * Initialize notes editor
     */
    initNotesEditor() {
        $('.editNotes').on('click', () => {
            $('.editNotes').html('<i class="fa-solid fa-pen-to-square"></i>Save').hide();
            $('.saveNotes').show();
            
            const currentNotes = $('.panel-notes .panel-body').html().trim();
            $('.panel-notes .panel-body').html(`<textarea cols="60" rows="5" id="notes" name="notes" class="form-control">${this.escapeHtml(currentNotes)}</textarea>`);
        });

        $('.panel-notes').on('click', '.panel-heading__button .saveNotes', () => {
            const notesVal = $('#notes').val();
            const data = { notes: notesVal };
            
            this.updateCompanyField(data, (responseData) => {
                $('.panel-notes .panel-body').html(responseData.notes || '');
                $('.editNotes').html('<i class="fa-solid fa-pen-to-square"></i>Edit Notes').show();
                $('.saveNotes').hide();
            });
        });
    }

    /**
     * Initialize risk level editor
     */
    initRiskEditor() {
        const self = this;
        $('#riskEdit .fa-pen-to-square').on('click', function() {
            const $riskSpan = $(this).parent().siblings('.currentCompanyRisk').children('span');
            const currentRisk = $riskSpan.data('risk-level') || '1';
            
            $riskSpan.html(`
                <select name="riskOptions" id="riskOptions" class="form-control">
                    <option value="1" ${currentRisk === '1' ? 'selected' : ''}>Low</option>
                    <option value="2" ${currentRisk === '2' ? 'selected' : ''}>Medium</option>
                    <option value="3" ${currentRisk === '3' ? 'selected' : ''}>High</option>
                </select>
            `);
            $(this).hide();
            $(this).siblings('.fa-check').show();
        });

        $('#riskEdit .fa-check').on('click', () => {
            const risk = $('#riskOptions').val();
            
            if (typeof ajaxRequest !== 'undefined') {
                if (showLoading && typeof showLoading === 'function') {
                    showLoading('#riskEdit .fa-check');
                }
                if (ajaxRequest && typeof ajaxRequest === 'function') {
                    ajaxRequest({
                        url: '/company/risk/update',
                        method: 'POST',
                        data: { company_id: self.companyId, risk_level: risk }
                }).done((response) => {
                    if (handleAjaxResponse && typeof handleAjaxResponse === 'function') {
                        handleAjaxResponse(response, (data) => {
                            const riskText = data.risk_level_text || 'Low';
                            $('.currentCompanyRisk span').html(riskText).data('risk-level', data.risk_level);
                            $('#riskEdit .fa-pen-to-square').show();
                            $('#riskEdit .fa-check').hide();
                            if (hideLoading && typeof hideLoading === 'function') {
                                hideLoading('#riskEdit .fa-check');
                            }
                        });
                    } else {
                        // Fallback if handleAjaxResponse not available
                        if (response.success) {
                            const riskText = response.data.risk_level_text || 'Low';
                            $('.currentCompanyRisk span').html(riskText).data('risk-level', response.data.risk_level);
                            $('#riskEdit .fa-pen-to-square').show();
                            $('#riskEdit .fa-check').hide();
                        }
                    }
                }).fail((xhr) => {
                    if (showError && typeof showError === 'function') {
                        showError(xhr.responseJSON?.error || 'Failed to update risk level');
                    }
                    if (hideLoading && typeof hideLoading === 'function') {
                        hideLoading('#riskEdit .fa-check');
                    }
                });
                }
            } else {
                // Fallback to jQuery AJAX if ajaxRequest is not available
                $.ajax({
                    url: '/company/risk/update',
                    method: 'POST',
                    data: { company_id: self.companyId, risk_level: risk },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).done((response) => {
                    if (response.success) {
                        const riskText = response.data.risk_level_text || 'Low';
                        $('.currentCompanyRisk span').html(riskText).data('risk-level', response.data.risk_level);
                        $('#riskEdit .fa-pen-to-square').show();
                        $('#riskEdit .fa-check').hide();
                    } else {
                        if (showError && typeof showError === 'function') {
                            showError(response.error);
                        }
                    }
                }).fail((xhr) => {
                    if (showError && typeof showError === 'function') {
                        showError(xhr.responseJSON?.error || 'Failed to update risk level');
                    }
                });
            }
        });
    }

    /**
     * Initialize tax residency editor
     */
    initTaxResidencyEditor() {
        const self = this;
        $('#taxResidencyRow .fa-pen-to-square').on('click', function() {
            const $taxResidency = $(this).parent().siblings('.taxResidency');
            const currentValue = $taxResidency.find('.taxResidencyVal').html().trim();
            
            let optionsHtml = '<option value="">country</option>';
            COUNTRIES.forEach(country => {
                const selected = currentValue === country ? 'selected' : '';
                optionsHtml += `<option value="${self.escapeHtml(country)}" ${selected}>${self.escapeHtml(country)}</option>`;
            });
            
            $taxResidency.html(`
                <tr>
                    <td style="border-top: 0px!important;padding:0;">
                        <select id="taxResidencyDropdown" class="form-control">${optionsHtml}</select>
                    </td>
                </tr>
            `);
            $(this).hide();
            $(this).siblings('.fa-check').show();
        });

        $('#taxResidencyRow .fa-check').on('click', () => {
            const value = $('#taxResidencyDropdown').val();
            const data = { tax_residency: value };
            
            self.updateCompanyField(data, (responseData) => {
                const displayValue = responseData.tax_residency || '';
                $('.taxResidency').html(`<span class="taxResidencyVal">${self.escapeHtml(displayValue)}</span>`);
                $('#taxResidencyRow .fa-pen-to-square').show();
                $('#taxResidencyRow .fa-check').hide();
            });
        });
    }

    /**
     * Initialize KYC editor
     */
    initKycEditor() {
        const self = this;
        $('.editKyc').on('click', function() {
            $(this).hide();
            const kycStart = $('#kycRow').data('kyc-start') || '';
            const kycEnd = $('#kycRow').data('kyc-end') || '';
            const kycReason = $('#kycRow').data('kyc-reason') || '';
            
            $('#kycRow').html(`
                <td><b>KYC Start:</b></td>
                <td>
                    Start: <input type="text" value="${self.escapeHtml(kycStart)}" id="updatedKycStart" class="form-control d-inline-block" style="width: auto;">
                    <br>
                    End: <input type="text" value="${self.escapeHtml(kycEnd)}" id="updatedKycEnd" class="form-control d-inline-block" style="width: auto;">
                    <br>
                    Reason: <input type="text" value="${self.escapeHtml(kycReason)}" id="updatedKycReason" class="form-control d-inline-block" style="width: auto;">
                </td>
                <td><i class="fa-solid fa-check" id="saveKyc" style="cursor: pointer;"></i></td>
            `);

            $('#updatedKycStart, #updatedKycEnd').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
        });

        $('.panel-details').on('click', '#saveKyc', () => {
            const data = {
                kyc: true,
                kyc_start: $('#updatedKycStart').val(),
                kyc_end: $('#updatedKycEnd').val(),
                kyc_reason: $('#updatedKycReason').val()
            };
            
            self.updateCompanyField(data, (responseData) => {
                // KYC fields might not be in response, so we'll just reload the row
                const displayHtml = `
                    <td><b>KYC Start:</b></td>
                    <td>
                        ${responseData.kyc_start || ''} - ${responseData.kyc_end || ''}
                        ${responseData.kyc_reason ? '<br>Reason: ' + self.escapeHtml(responseData.kyc_reason) : ''}
                    </td>
                    <td><i class="fa-solid fa-pen-to-square editKyc" style="cursor: pointer;"></i></td>
                `;
                $('#kycRow').html(displayHtml);
                $('#kycRow').data('kyc-start', responseData.kyc_start);
                $('#kycRow').data('kyc-end', responseData.kyc_end);
                $('#kycRow').data('kyc-reason', responseData.kyc_reason);
                self.initKycEditor();
            });
        });
    }

    /**
     * Initialize activity code editors
     */
    initActivityCodeEditors() {
        const self = this;
        // Activity code editor
        this.initTextFieldEditor('.editActivityCode', '#activityCodeRow', 'activity_code', 'Activity Code:', 'updatedActivityCode', 'saveActivityCode');
        
        // Activity code description editor
        $('.editActivityCodeDescription').on('click', function() {
            $(this).hide();
            const currentValue = $('#activityCodeDescriptionRow').data('current-value') || '';
            
            $('#activityCodeDescriptionRow').html(`
                <td><b>Activity Code Description:</b></td>
                <td><textarea id="updatedActivityCodeDescription" rows="3" style="width: 100%;" class="form-control">${self.escapeHtml(currentValue)}</textarea></td>
                <td><i class="fa-solid fa-check" id="saveActivityCodeDescription" style="cursor: pointer;"></i></td>
            `);
        });

        $('.panel-details').on('click', '#saveActivityCodeDescription', () => {
            const value = $('#updatedActivityCodeDescription').val();
            const data = { activity_code_description: value };
            
            self.updateCompanyField(data, (responseData) => {
                const displayValue = responseData.activity_code_description || '';
                $('#activityCodeDescriptionRow').html(`
                    <td><b>Activity Code Description:</b></td>
                    <td>${self.escapeHtml(displayValue)}</td>
                    <td><i class="fa-solid fa-pen-to-square editActivityCodeDescription" style="cursor: pointer;"></i></td>
                `);
                $('#activityCodeDescriptionRow').data('current-value', displayValue);
                self.initActivityCodeEditors();
            });
        });
    }

    /**
     * Initialize related entities management
     */
    initRelatedEntities() {
        // Delete related person
        $('.relatedPersons').on('click', '.fa-xmark.relatedPerson', (e) => {
            e.preventDefault();
            if (window.confirm("Remove Related Person?")) {
                const personID = $(e.target).data('personid');
                const relation = $(e.target).data('relation');
                
                $.ajax({
                    url: `/companies/${this.companyId}/client/delete`,
                    method: 'POST',
                    data: {
                        company_id: this.companyId,
                        person_id: personID,
                        relation: relation
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).done((response) => {
                    if (response.success) {
                        $(e.target).closest('tr, .related-person-item').fadeOut(300, function() {
                            $(this).remove();
                        });
                        if (showSuccess && typeof showSuccess === 'function') {
                            showSuccess('Related person removed successfully');
                        }
                    } else {
                        if (showError && typeof showError === 'function') {
                            showError(response.error);
                        }
                    }
                }).fail((xhr) => {
                    if (showError && typeof showError === 'function') {
                        showError(xhr.responseJSON?.error || 'Failed to remove related person');
                    }
                });
            }
        });

        // Delete related company
        $('.relatedPersons').on('click', '.fa-xmark.relatedCompany', (e) => {
            e.preventDefault();
            if (window.confirm("Remove Related Company?")) {
                const relatedCompanyID = $(e.target).data('relatedcompanyid');
                const relation = $(e.target).data('relation');
                
                $.ajax({
                    url: `/companies/${this.companyId}/company/delete`,
                    method: 'POST',
                    data: {
                        company_id: this.companyId,
                        relatedCompany_id: relatedCompanyID,
                        relation: relation
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).done((response) => {
                    if (response.success) {
                        $(e.target).closest('tr, .related-company-item').fadeOut(300, function() {
                            $(this).remove();
                        });
                        if (showSuccess && typeof showSuccess === 'function') {
                            showSuccess('Related company removed successfully');
                        }
                    } else {
                        if (showError && typeof showError === 'function') {
                            showError(response.error);
                        }
                    }
                }).fail((xhr) => {
                    if (showError && typeof showError === 'function') {
                        showError(xhr.responseJSON?.error || 'Failed to remove related company');
                    }
                });
            }
        });
    }

    /**
     * Update company field via AJAX
     */
    updateCompanyField(data, onSuccess) {
        if (typeof ajaxRequest !== 'undefined') {
            showLoading('.saveEdit, #saveDate, #saveVat, #saveRegCountry, #saveKyc, #saveActivityCode, #saveActivityCodeDescription, .saveNotes');
            ajaxRequest({
                url: this.updateUrl,
                method: 'PUT',
                data: data
            }).done((response) => {
                if (handleAjaxResponse && typeof handleAjaxResponse === 'function') {
                    handleAjaxResponse(response, (responseData) => {
                        if (onSuccess) onSuccess(responseData);
                        hideLoading('.saveEdit, #saveDate, #saveVat, #saveRegCountry, #saveKyc, #saveActivityCode, #saveActivityCodeDescription, .saveNotes');
                        if (showSuccess && typeof showSuccess === 'function') {
                            showSuccess(response.message || 'Field updated successfully');
                        }
                    });
                } else {
                    // Fallback
                    if (response.success) {
                        if (onSuccess) onSuccess(response.data);
                        hideLoading('.saveEdit, #saveDate, #saveVat, #saveRegCountry, #saveKyc, #saveActivityCode, #saveActivityCodeDescription, .saveNotes');
                    }
                }
            }).fail((xhr) => {
                hideLoading('.saveEdit, #saveDate, #saveVat, #saveRegCountry, #saveKyc, #saveActivityCode, #saveActivityCodeDescription, .saveNotes');
                if (showError && typeof showError === 'function') {
                    const error = xhr.responseJSON?.error || xhr.responseJSON || 'Failed to update field';
                    showError(error);
                }
            });
        } else {
            // Fallback to jQuery AJAX
            $.ajax({
                url: this.updateUrl,
                method: 'PUT',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done((response) => {
                if (response.success) {
                    if (onSuccess) onSuccess(response.data);
                    if (showSuccess && typeof showSuccess === 'function') {
                        showSuccess(response.message || 'Field updated successfully');
                    }
                } else {
                    if (showError && typeof showError === 'function') {
                        showError(response.error);
                    }
                }
            }).fail((xhr) => {
                if (showError && typeof showError === 'function') {
                    const error = xhr.responseJSON?.error || xhr.responseJSON || 'Failed to update field';
                    showError(error);
                }
            });
        }
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        if (text == null) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CompanyEditor;
}

// Make available globally
window.CompanyEditor = CompanyEditor;

