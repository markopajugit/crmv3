@extends('layouts.app')

@section('content')
<div data-page="person-show" data-entity-id="{{ $person->id }}">
<div id="loading" style="display:none;
    width: 500px;
    z-index: 10;
    font-size: 50px;
    background: white;
    text-align: center;
    position: fixed;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center;
    </script>
    <style>
        table.formattedtable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .formattedtable td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .formattedtable tr:nth-child(even) {
            background-color: #dddddd;
        }

        td .fa-plus {
            color:green;
        }
    </style>
    <a style="float:right;background: darkred!important;" target="_blank" class="btn btn-primary" data-personid="{{$person->id}}" id="deletePerson"><i class="fa-solid fa-trash"></i>Delete Person</a>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1><i class="fa-solid fa-user"></i>{{ $person->name }}<i class="fa-solid fa-pen-to-square"
                                                                      style="vertical-align: middle; margin-left: 10px;font-size: 20px;"></i>
            </h1>
            <!--<a class="btn btn-primary" href="{{ route('persons.edit',$person->id) }}">Edit</a>-->
            <h6>{{ $person->date_of_birth }}<br>
            {{ $person->id_code }} @if($person->country) - {{$person->country}}@endif
                @if($person->id_code_est)
                    <br>{{$person->id_code_est}} - Estonia
                @endif</h6>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">
                        <button type="button" class="btn saveDetails" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr id="idRow">
                            <td style="width:50%"><strong>ID code:</strong></td>
                            <td><div id="idcode">{{ $person->id_code }}@if($person->country) - {{$person->country}}@endif</div>
                                @if($person->id_code_est)
                                    <div id="idcodeEst">{{$person->id_code_est}} - Estonia</div>
                                @endif
                            </td>
                            <td><i class="fa-solid fa-pen-to-square" data-idcode="{{$person->id_code}}" data-country="{{$person->country}}" data-estid="{{$person->id_code_est}}"></i></td>
                            <td></td>
                        </tr>
                        <!--<tr id="addressRow">
                            <td style="width:50%"><strong>Address:</strong></td>
                            <td id="currentAddress">
                                @if($person->address_street)
                                    {{ $person->address_street }}
                                @endif

                                @if($person->address_city)
                                    <br>{{ $person->address_city }}
                                @endif

                                @if($person->address_zip)
                                    <br>{{ $person->address_zip }}
                                @endif

                                @if($person->address_dropdown)
                                    <br>{{ $person->address_dropdown }}</td>
                                @endif
                            <td><i class="fa-solid fa-pen-to-square editDetails"></i></td>
                            <td></td>
                        </tr>-->
                        <tr id="addressRow">
                            <td style="width:50%"><strong>Address:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactAddress" data-contactid="0"><span class="addressStreet">{{ $person->address_street }}</span>@if($person->address_city), <span class="addressCity">{{ $person->address_city }}</span>@endif <br>
                                            <span class="addressZip">{{ $person->address_zip }}</span>@if($person->address_dropdown), <span class="addressCountry">{{ $person->address_dropdown }}</span>@endif
                                            @if($person->address_note)<br> <i class="fa-regular fa-comment"></i> <span class="addressNote">{{ $person->address_note }}</span>@endif</td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                    </tr>
                                    @foreach($person->getAddresses as $address)
                                    <tr>
                                        <td style="border-top: 0!important; padding:0;" class="contactAddress" data-contactid="{{$address->id}}"><span class="addressStreet">{{ $address->street }}</span>@if($address->city), <span class="addressCity">{{ $address->city }}</span>@endif <br>
                                            <span class="addressZip">{{ $address->zip }}</span>@if($address->country), <span class="addressCountry">{{ $address->country }}</span>@endif
                                            @if($address->note)<br> <i class="fa-regular fa-comment"></i> <span class="addressNote">{{ $address->note }}</span>@endif</td>
                                        <td style="border-top: 0!important; padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important; padding:0;text-align: center;"><i style="color:darkred;" class="fa-solid fa-trash" data-contactid="{{$address->id}}"></i></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr id="emailRow">
                            <td style="width:50%"><strong>E-mail:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactEmail" data-contactid="0"><span class="contactEmailVal">{{ $person->email }}</span>
                                            @if($person->email_note)<br> <i class="fa-regular fa-comment"></i> <span class="contactEmailNote">{{ $person->email_note }}</span>@endif</td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                    </tr>
                                    @foreach($person->getContacts as $contact)
                                        @if($contact->type == 'email')
                                            <tr>
                                                <td style="border-top: 0!important; padding:0;" class="contactEmail" data-contactid="{{$contact->id}}"><span class="contactEmailVal">{{ $contact->value }}</span>
                                                    @if($contact->note)<br> <i class="fa-regular fa-comment"></i> <span class="contactEmailNote">{{ $contact->note }}</span>@endif</td>
                                                <td style="border-top: 0!important; padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                                <td style="border-top: 0!important; padding:0;text-align: center;"><i style="color:darkred;" class="fa-solid fa-trash" data-contactid="{{$contact->id}}"></i></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr id="phoneRow">
                            <td style="width:50%"><strong>Phone:</strong></td>
                            <td colspan="3">
                            <table width="100%">
                                <tr>
                                    <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactPhone" data-contactid="0"><span class="contactPhoneVal">{{ $person->phone }}</span>
                                        @if($person->phone_note)<br> <i class="fa-regular fa-comment"></i> <span class="contactPhoneNote">{{ $person->phone_note }}</span>@endif
                                    </td>
                                    <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                    <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                </tr>
                            @foreach($person->getContacts as $contact)
                                @if($contact->type == 'phone')
                                    <tr>
                                        <td style="border-top: 0!important; padding:0;" class="contactPhone" data-contactid="{{$contact->id}}"><span class="contactPhoneVal">{{ $contact->value }}</span>
                                            @if($contact->note)<br> <i class="fa-regular fa-comment"></i> <span class="contactPhoneNote">{{ $contact->note }}</span>@endif</td>
                                    <td style="border-top: 0!important; padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                    <td style="border-top: 0!important; padding:0;text-align: center;"><i style="color:darkred;" class="fa-solid fa-trash" data-contactid="{{$contact->id}}"></i></td>
                                    </tr>
                                @endif
                            @endforeach
                            </table>
                            </td>
                        </tr>
                        <tr id="taxResidencyRow">
                            <td style="width:50%"><strong>Tax Residencies:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;">
                                            @if($person->taxResidencies->count() > 0)
                                                @foreach($person->taxResidencies as $taxResidency)
                                                    <div class="tax-residency-item" style="border-bottom: 1px solid #ddd; padding: 5px 0; margin-bottom: 5px;">
                                                        <table width="100%" style="margin: 0;">
                                                            <tr>
                                                                <td style="border: none; padding: 2px;">
                                                                    <strong class="tax-residency-country">{{ $taxResidency->country }}</strong>
                                                                    @if($taxResidency->is_primary)
                                                                        <span class="badge badge-primary" style="background-color: #007bff; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 5px;">PRIMARY</span>
                                                                    @endif
                                                                    <br>
                                                                    @if($taxResidency->valid_from || $taxResidency->valid_to)
                                                                        <small style="color: #666;">
                                                                            @if($taxResidency->valid_from)From: {{ $taxResidency->valid_from->format('d.m.Y') }}@endif
                                                                            @if($taxResidency->valid_to) | To: {{ $taxResidency->valid_to->format('d.m.Y') }}@endif
                                                                        </small><br>
                                                                    @endif
                                                                    @if($taxResidency->notes)
                                                                        <small style="color: #666; font-style: italic;">{{ $taxResidency->notes }}</small>
                                                                    @endif
                                                                </td>
                                                                <td style="border: none; padding: 2px; text-align: center; width: 80px; vertical-align: top;">
                                                                    
                                                                    <i class="fa-solid fa-trash delete-tax-residency" data-id="{{ $taxResidency->id }}" style="cursor: pointer; color: #dc3545;"></i>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div id="noTaxResidencies" style="color: #999; font-style: italic;">No tax residencies added</div>
                                            @endif
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;">
                                            
                                                <i class="fa-solid fa-plus" id="addTaxResidencyBtn"></i>
                                            
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"></td>
                                    </tr>
                                    <!-- New Tax Residency Form Row -->
                                    <tr id="newTaxResidencyRow" style="display: none;">
                                        <td colspan="3" style="border-top: 0px!important; padding: 10px;">
                                            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
                                                <h6 style="margin-bottom: 10px; color: #495057;">Add New Tax Residency</h6>
                                                <div style="margin-bottom: 10px;">
                                                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Country:</label>
                                                    <select id="newTaxResidencyCountry" style="width: 100%; padding: 5px;">
                                                        <option value="">Select Country</option>
                                                        <option value="Afghanistan">Afghanistan</option>
                                                        <option value="Albania">Albania</option>
                                                        <option value="Algeria">Algeria</option>
                                                        <option value="Andorra">Andorra</option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Argentina">Argentina</option>
                                                        <option value="Armenia">Armenia</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Austria">Austria</option>
                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                        <option value="Bahamas">Bahamas</option>
                                                        <option value="Bahrain">Bahrain</option>
                                                        <option value="Bangladesh">Bangladesh</option>
                                                        <option value="Barbados">Barbados</option>
                                                        <option value="Belarus">Belarus</option>
                                                        <option value="Belgium">Belgium</option>
                                                        <option value="Belize">Belize</option>
                                                        <option value="Benin">Benin</option>
                                                        <option value="Bhutan">Bhutan</option>
                                                        <option value="Bolivia">Bolivia</option>
                                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                        <option value="Botswana">Botswana</option>
                                                        <option value="Brazil">Brazil</option>
                                                        <option value="Brunei">Brunei</option>
                                                        <option value="Bulgaria">Bulgaria</option>
                                                        <option value="Burkina Faso">Burkina Faso</option>
                                                        <option value="Burundi">Burundi</option>
                                                        <option value="Cambodia">Cambodia</option>
                                                        <option value="Cameroon">Cameroon</option>
                                                        <option value="Canada">Canada</option>
                                                        <option value="Cape Verde">Cape Verde</option>
                                                        <option value="Central African Republic">Central African Republic</option>
                                                        <option value="Chad">Chad</option>
                                                        <option value="Chile">Chile</option>
                                                        <option value="China">China</option>
                                                        <option value="Colombia">Colombia</option>
                                                        <option value="Comoros">Comoros</option>
                                                        <option value="Congo">Congo</option>
                                                        <option value="Costa Rica">Costa Rica</option>
                                                        <option value="Croatia">Croatia</option>
                                                        <option value="Cuba">Cuba</option>
                                                        <option value="Cyprus">Cyprus</option>
                                                        <option value="Czech Republic">Czech Republic</option>
                                                        <option value="Denmark">Denmark</option>
                                                        <option value="Djibouti">Djibouti</option>
                                                        <option value="Dominica">Dominica</option>
                                                        <option value="Dominican Republic">Dominican Republic</option>
                                                        <option value="Ecuador">Ecuador</option>
                                                        <option value="Egypt">Egypt</option>
                                                        <option value="El Salvador">El Salvador</option>
                                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                        <option value="Eritrea">Eritrea</option>
                                                        <option value="Estonia">Estonia</option>
                                                        <option value="Ethiopia">Ethiopia</option>
                                                        <option value="Fiji">Fiji</option>
                                                        <option value="Finland">Finland</option>
                                                        <option value="France">France</option>
                                                        <option value="Gabon">Gabon</option>
                                                        <option value="Gambia">Gambia</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Germany">Germany</option>
                                                        <option value="Ghana">Ghana</option>
                                                        <option value="Greece">Greece</option>
                                                        <option value="Grenada">Grenada</option>
                                                        <option value="Guatemala">Guatemala</option>
                                                        <option value="Guinea">Guinea</option>
                                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                        <option value="Guyana">Guyana</option>
                                                        <option value="Haiti">Haiti</option>
                                                        <option value="Honduras">Honduras</option>
                                                        <option value="Hungary">Hungary</option>
                                                        <option value="Iceland">Iceland</option>
                                                        <option value="India">India</option>
                                                        <option value="Indonesia">Indonesia</option>
                                                        <option value="Iran">Iran</option>
                                                        <option value="Iraq">Iraq</option>
                                                        <option value="Ireland">Ireland</option>
                                                        <option value="Israel">Israel</option>
                                                        <option value="Italy">Italy</option>
                                                        <option value="Jamaica">Jamaica</option>
                                                        <option value="Japan">Japan</option>
                                                        <option value="Jordan">Jordan</option>
                                                        <option value="Kazakhstan">Kazakhstan</option>
                                                        <option value="Kenya">Kenya</option>
                                                        <option value="Kiribati">Kiribati</option>
                                                        <option value="Korea, North">Korea, North</option>
                                                        <option value="Korea, South">Korea, South</option>
                                                        <option value="Kuwait">Kuwait</option>
                                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                        <option value="Laos">Laos</option>
                                                        <option value="Latvia">Latvia</option>
                                                        <option value="Lebanon">Lebanon</option>
                                                        <option value="Lesotho">Lesotho</option>
                                                        <option value="Liberia">Liberia</option>
                                                        <option value="Libya">Libya</option>
                                                        <option value="Liechtenstein">Liechtenstein</option>
                                                        <option value="Lithuania">Lithuania</option>
                                                        <option value="Luxembourg">Luxembourg</option>
                                                        <option value="Madagascar">Madagascar</option>
                                                        <option value="Malawi">Malawi</option>
                                                        <option value="Malaysia">Malaysia</option>
                                                        <option value="Maldives">Maldives</option>
                                                        <option value="Mali">Mali</option>
                                                        <option value="Malta">Malta</option>
                                                        <option value="Marshall Islands">Marshall Islands</option>
                                                        <option value="Mauritania">Mauritania</option>
                                                        <option value="Mauritius">Mauritius</option>
                                                        <option value="Mexico">Mexico</option>
                                                        <option value="Micronesia">Micronesia</option>
                                                        <option value="Moldova">Moldova</option>
                                                        <option value="Monaco">Monaco</option>
                                                        <option value="Mongolia">Mongolia</option>
                                                        <option value="Montenegro">Montenegro</option>
                                                        <option value="Morocco">Morocco</option>
                                                        <option value="Mozambique">Mozambique</option>
                                                        <option value="Myanmar">Myanmar</option>
                                                        <option value="Namibia">Namibia</option>
                                                        <option value="Nauru">Nauru</option>
                                                        <option value="Nepal">Nepal</option>
                                                        <option value="Netherlands">Netherlands</option>
                                                        <option value="New Zealand">New Zealand</option>
                                                        <option value="Nicaragua">Nicaragua</option>
                                                        <option value="Niger">Niger</option>
                                                        <option value="Nigeria">Nigeria</option>
                                                        <option value="North Macedonia">North Macedonia</option>
                                                        <option value="Norway">Norway</option>
                                                        <option value="Oman">Oman</option>
                                                        <option value="Pakistan">Pakistan</option>
                                                        <option value="Palau">Palau</option>
                                                        <option value="Panama">Panama</option>
                                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                                        <option value="Paraguay">Paraguay</option>
                                                        <option value="Peru">Peru</option>
                                                        <option value="Philippines">Philippines</option>
                                                        <option value="Poland">Poland</option>
                                                        <option value="Portugal">Portugal</option>
                                                        <option value="Qatar">Qatar</option>
                                                        <option value="Romania">Romania</option>
                                                        <option value="Russia">Russia</option>
                                                        <option value="Rwanda">Rwanda</option>
                                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                        <option value="Saint Lucia">Saint Lucia</option>
                                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                        <option value="Samoa">Samoa</option>
                                                        <option value="San Marino">San Marino</option>
                                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                                        <option value="Senegal">Senegal</option>
                                                        <option value="Serbia">Serbia</option>
                                                        <option value="Seychelles">Seychelles</option>
                                                        <option value="Sierra Leone">Sierra Leone</option>
                                                        <option value="Singapore">Singapore</option>
                                                        <option value="Slovakia">Slovakia</option>
                                                        <option value="Slovenia">Slovenia</option>
                                                        <option value="Solomon Islands">Solomon Islands</option>
                                                        <option value="Somalia">Somalia</option>
                                                        <option value="South Africa">South Africa</option>
                                                        <option value="South Sudan">South Sudan</option>
                                                        <option value="Spain">Spain</option>
                                                        <option value="Sri Lanka">Sri Lanka</option>
                                                        <option value="Sudan">Sudan</option>
                                                        <option value="Suriname">Suriname</option>
                                                        <option value="Sweden">Sweden</option>
                                                        <option value="Switzerland">Switzerland</option>
                                                        <option value="Syria">Syria</option>
                                                        <option value="Taiwan">Taiwan</option>
                                                        <option value="Tajikistan">Tajikistan</option>
                                                        <option value="Tanzania">Tanzania</option>
                                                        <option value="Thailand">Thailand</option>
                                                        <option value="Timor-Leste">Timor-Leste</option>
                                                        <option value="Togo">Togo</option>
                                                        <option value="Tonga">Tonga</option>
                                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                        <option value="Tunisia">Tunisia</option>
                                                        <option value="Turkey">Turkey</option>
                                                        <option value="Turkmenistan">Turkmenistan</option>
                                                        <option value="Tuvalu">Tuvalu</option>
                                                        <option value="Uganda">Uganda</option>
                                                        <option value="Ukraine">Ukraine</option>
                                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                        <option value="United States">United States</option>
                                                        <option value="Uruguay">Uruguay</option>
                                                        <option value="Uzbekistan">Uzbekistan</option>
                                                        <option value="Vanuatu">Vanuatu</option>
                                                        <option value="Vatican City">Vatican City</option>
                                                        <option value="Venezuela">Venezuela</option>
                                                        <option value="Vietnam">Vietnam</option>
                                                        <option value="Yemen">Yemen</option>
                                                        <option value="Zambia">Zambia</option>
                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                    </select>
                                                </div>
                                                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                                    <div style="flex: 1;">
                                                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Valid From:</label>
                                                        <input type="text" id="newTaxResidencyValidFrom" placeholder="dd.mm.yyyy" style="width: 100%; padding: 5px;">
                                                    </div>
                                                    <div style="flex: 1;">
                                                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Valid To:</label>
                                                        <input type="text" id="newTaxResidencyValidTo" placeholder="dd.mm.yyyy" style="width: 100%; padding: 5px;">
                                                    </div>
                                                </div>
                                                <div style="margin-bottom: 10px;">
                                                    <label style="font-weight: bold;">
                                                        <input type="checkbox" id="newTaxResidencyPrimary" style="margin-right: 5px;">
                                                        Set as primary tax residency
                                                    </label>
                                                </div>
                                                <div style="margin-bottom: 15px;">
                                                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Notes:</label>
                                                    <textarea id="newTaxResidencyNotes" placeholder="Optional notes..." style="width: 100%; height: 60px; padding: 5px; resize: vertical;"></textarea>
                                                </div>
                                                <div style="text-align: right;">
                                                    <button type="button" id="cancelNewTaxResidency" class="btn btn-sm btn-secondary" style="margin-right: 10px;">Cancel</button>
                                                    <button type="button" id="saveNewTaxResidency" class="btn btn-sm btn-success">Save Tax Residency</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id="riskRow">
                            <td style="width:50%"><strong>Risk:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width: 200px; border-top: 0!important; padding:0;" class="currentCompanyRisk">
                                            @if($person->getCurrentRisk)
                                                @if($person->getCurrentRisk->risk_level == 1)
                                                    <span style="color: green;">LOW</span>
                                                @elseif($person->getCurrentRisk->risk_level == 2)
                                                    <span style="color: orange;">MEDIUM</span>
                                                @elseif($person->getCurrentRisk->risk_level == 3)
                                                    <span style="color: red;">HIGH</span>
                                                @endif
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;" id="riskEdit"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <!--<td style="border-top: 0!important;padding:0;text-align: center; cursor: pointer;" id="showRiskHistory">Show history</td>-->
                                    </tr>
                                    @foreach($person->getRisksHistory as $risk)
                                        @php
                                            $user = \App\Models\User::find($risk->user_id);
                                        @endphp
                                        <tr style="display:none;" class="riskHistoryRows">
                                            <td style="min-width: 200px; border-top: 0!important; padding:0;" class="companyRisk" data-contactid="{{$risk->id}}">
                                                @if($risk->risk_level == 1)
                                                    <span style="color: green;">LOW</span>  - <i class="fa-solid fa-user"></i>{{$user->name}} - {{$risk->updated_at}}
                                                @elseif($risk->risk_level == 2)
                                                    <span style="color: orange;">MEDIUM</span>  - <i class="fa-solid fa-user"></i>{{$user->name}} - {{$risk->updated_at}}
                                                @elseif($risk->risk_level == 3)
                                                    <span style="color: red;">HIGH</span>  - <i class="fa-solid fa-user"></i>{{$user->name}} - {{$risk->updated_at}}
                                                @endif
                                            </td>
                                            <!--<td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                            <td style="border-top: 0!important;padding:0;text-align: center;">Show history</td>-->
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr id="birthplaceCountryRow">
                            <td style="width:50%"><strong>Birthplace Country:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tbody><tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="birthplaceCountry"><span class="birthplaceCountryVal">@if($person->birthplace_country) {{$person->birthplace_country}} @endif</span>
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"></td>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                        <tr id="birthplaceCityRow">
                            <td style="width:50%"><strong>Birthplace City:</strong></td>
                            <td id="currentBirthplaceCity">{{ $person->birthplace_city }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editBirthplaceCity"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr id="citizenshipRow">
                            <td style="width:50%"><strong>Citizenship:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tbody><tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="citizenship"><span class="citizenshipVal">@if($person->citizenship) {{$person->citizenship}} @endif</span>
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"></td>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                        <tr id="pepRow">
                            <td style="width:50%"><strong>PEP (Politically Exposed Person):</strong></td>
                            <td id="currentPep">
                                @if($person->pep == 1)
                                    <span style="color: red;">Yes</span>
                                @elseif($person->pep == 0)
                                    <span style="color: green;">No</span>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editPep"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="panel panel-default panel-kyc">
                <div class="panel-heading">
                    <div class="panel-heading__title">KYC Monitoring</div>
                    <div class="panel-heading__button">
                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addKycModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add monitoring info
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    @foreach($person->kycs()->latest()->get() as $kyc)
                        <div style="border-bottom: 1px solid black; padding: 5px 0;" class="kyc-record">
                            <b>{{ $kyc->responsibleUser ? $kyc->responsibleUser->name : 'N/A' }} ({{ $kyc->created_at->format('d.m.Y') }})</b>
                            <i class="fa-solid fa-pen-to-square kyc-edit" style="vertical-align: middle; margin-left: 10px;font-size: 20px;cursor: pointer;" data-kycid="{{ $kyc->id }}"></i>
                            <i class="fa-solid fa-trash kyc-delete" style="vertical-align: middle; margin-left: 10px;font-size: 20px;cursor: pointer;" data-kycid="{{ $kyc->id }}"></i>
                            <br>
                            <div class="kyc-details">
                                @if($kyc->start_date || $kyc->end_date)
                                    Monitoring date: {{ $kyc->start_date }}, next monitoring date: {{ $kyc->end_date }}<br>
                                @endif
                                @if($kyc->risk)
                                    <strong>Risk:</strong> {{ $kyc->risk }}<br>
                                @endif
                                @if($kyc->documents)
                                    <strong>Documents:</strong> {{ $kyc->documents }}<br>
                                @endif
                                @if($kyc->comments)
                                    <strong>Comments:</strong> {!! nl2br(e($kyc->comments)) !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                    @if($person->kycs()->count() == 0)
                        <p>No KYC monitoring records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-notes">
                <div class="panel-heading">
                    <div class="panel-heading__title">Notes</div>
                    <div class="panel-heading__button">


                        <!--<button type="button" class="btn editNotes">
                            <i class="fa-solid fa-pen-to-square"></i>Edit
                        </button>-->

                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addNote">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add Note
                        </button>

                        <button type="button" class="btn saveNotes" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    @if($person->notes)
                        <div>Vana note: {{$person->notes}}</div>
                    @endif
                    @foreach($person->getNotes as $note)
                        <div style="border-bottom: 1px solid black; padding: 5px 0;" class="note"><b>{{$note->responsible_user($note->user_id)->name}} ({{$note->created_at}})</b><i class="fa-solid fa-pen-to-square" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-check" style="display:none;vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-trash" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><br>
                            <div class="noteContent" data-content="{{$note->content}}">{!! nl2br(e($note->content)) !!}</div></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-heading__title">Relations</div>
                    <div class="panel-heading__button">

                        <!--<button type="button" class="btn editRelatedPersons">
                            <i class="fa-solid fa-pen-to-square"></i>Edit Related Person
                        </button>-->
                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#relatedCompany">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add Relation
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table relatedPersons">
                        @if(!$person->companies->isEmpty())
                            @foreach ($person->companies as $company)
                                <tr>
                                    <td style="width:50%"><i class="fa-solid fa-building" style="margin-right: 5px;"></i><a
                                            href="/companies/{{$company->id}}">{{$company->name}}</a></td>
                                    <td>
                                        @if($company->pivot->relation == 'Main Contact')
                                            <b>{{ $company->pivot->relation }}</b>
                                        @else
                                            {{ $company->pivot->relation }}
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-pen-to-square"
                                           style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                                           data-companyID="{{$company->id}}" data-relation="{{ $company->pivot->relation }}" data-companyName="{{$company->name}}"></i>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-xmark"
                                           style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                                           data-companyID="{{$company->id}}" data-relation="{{ $company->pivot->relation }}"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @forelse ($relatedPersons as $relatedPerson)
                            <tr>
                                <td style="width:50%"><i class="fa-solid fa-user" style="margin-right: 5px;"></i><a
                                        href="/persons/{{$relatedPerson['person_id']}}">{{$relatedPerson['name']}}</a></td>

                                <td>
                                    {{ $relatedPerson['relation'] }}
                                </td>
                                <td>
                                    <i class="fa-solid fa-pen-to-square"
                                       style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                                       data-personID="{{ $relatedPerson['person_id'] }}" data-relation="{{ $relatedPerson['relation'] }}" data-personName="{{$relatedPerson['name']}}"></i>
                                </td>
                                <td>
                                    <i class="fa-solid fa-xmark"
                                       style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                                       data-personID="{{ $relatedPerson['person_id'] }}" data-relation="{{ $relatedPerson['relation'] }}"></i>
                                </td>
                            </tr>
                        @empty
                        @endforelse

                    </table>
                    <table class="table relatedCompaniesEdit" style="display:none;">
                        <form id="updateRelatedCompany" action="{{ route('persons.update',$person->id) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="companyID" value="">

                                @foreach ($person->companies as $company)
                                    <tr>
                                        <td style="width:50%"><i class="fa-solid fa-user" style="margin-right: 5px;"></i><a
                                                href="/companies/{{$company->id}}">{{$company->name}}</a></td>
                                        <td>
                                            <select name="relation[]" id="relation">
                                                <option value="shareholder">Shareholder</option>
                                                <option value="agent">Agent</option>
                                            </select>
                                            <input type="hidden" name="companies[]" value="{{$company->id}}">

                                        </td>
                                    </tr>
                                @endforeach

                        </form>
                    </table>

                </div>
            </div>

        </div>
        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-heading__title">Orders</div>
                    <div class="panel-heading__button">
                        <!--<button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addOrderModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add Order
                        </button>-->
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table relatedPersons">
                        @foreach ($person->orders as $order)
                            <tr>
                                <td style="width:50%"><i class="fa-solid fa-file" style="margin-right: 5px;"></i><a
                                        href="/orders/{{$order->id}}"><b>{{$order->number}}</b> {{$order->name}}</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-documents regularDocuments">
                <div class="panel-heading">
                    <div class="panel-heading__title">Documents <span id="documentsCount"></span></div>

                </div>
                <div class="panel-body">
                    <table class="table">
                        @foreach ($person->files as $file)
                            @if(!$file->virtual_office)
                            <tr>
                                <td><i class="fa-solid fa-file-arrow-up"></i>
                                    <b id="orderArchiveNumber-{{$file->id}}" data-fileid="{{$file->id}}">{{$file->archive_nr}}</b>    {{$file->name}}
                                </td>
                                <td>{{$file->created_at->format('d.m.Y H:i:s')}}</td>
                                <td>
                                    <button type="button" class="btn editArchiveNumber" data-fileid="{{$file->id}}">
                                        <i class="fa-solid fa-pen-to-square"></i>Edit Archive number
                                    </button>
                                    @if(!$file->archive_nr)
                                        <button type="button" class="btn generateArchiveNumber" data-fileid="{{$file->id}}">
                                            Generate Archive number
                                        </button>
                                    @endif
                                    <button style="display:none;" type="button" class="btn saveArchiveNumber" data-fileid="{{$file->id}}">
                                        <i class="fa-solid fa-check"></i>Save Archive number
                                    </button>
                                </td>
                                <td><a href="/file/person/{{ $person->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                <td><a href="/file/download/person/{{ $person->id }}/{{$file->name}}"target="_blank">DOWNLOAD</a></td>
                                <td><a class="deleteDocument" href="/file/delete/person/{{ $person->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a></td>
                            </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-documents VOdocuments">
                <div class="panel-heading">
                    <div class="panel-heading__title">Virtual Office Documents <span id="VOdocumentsCount"></span></div>

                </div>
                <div class="panel-body">
                    <table class="table">
                        @foreach ($person->files as $file)
                            @if($file->virtual_office)
                                <tr>
                                    <td><!--<i class="fa-solid fa-file-arrow-up"></i>
                                    <b id="orderArchiveNumber-{{$file->id}}" data-fileid="{{$file->id}}">{{$file->archive_nr}}</b>-->    {{$file->name}}
                                    </td>
                                    <td>
                                        <!--<button type="button" class="btn editArchiveNumber" data-fileid="{{$file->id}}">
                                        <i class="fa-solid fa-pen-to-square"></i>Edit Archive number
                                    </button>
                                    <button style="display:none;" type="button" class="btn saveArchiveNumber" data-fileid="{{$file->id}}">
                                        <i class="fa-solid fa-check"></i>Save Archive number
                                    </button>-->
                                    </td>
                                    <td>{{$file->created_at->format('d.m.Y H:i:s')}}</td>
                                    <td><a href="/file/person/{{ $person->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                    <td><a href="/file/download/person/{{ $person->id }}/{{$file->name}}"target="_blank">DOWNLOAD</a></td>
                                    <td><a class="deleteDocument" href="/file/delete/person/{{ $person->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a></td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-documents-dropzone">
                <div class="panel-heading">
                    <div class="panel-heading__title">Upload Documents</div>
                </div>
                <div class="panel-body">
                    <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">
                        @csrf
                    </form>
                    <div align="center">
                        <button type="button" class="btn btn-info" id="submit-all">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-documents-dropzone">
                <div class="panel-heading">
                    <div class="panel-heading__title">Upload Virtual Office Documents</div>
                </div>
                <div class="panel-body">
                    <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload-virtual-document') }}">
                        @csrf
                    </form>
                    <div align="center">
                        <button type="button" class="btn btn-info" id="submit-all">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="addOrderModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Order</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="companyID" class="form-control" value="{{ $person->id }}">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="mb-3">
                            <label for="personName" class="form-label">Person</label>
                            <input type="text" id="personName" class="form-control" value="{{ $person->name }}"
                                   disabled>
                        </div>

                        <!--<div>Type</div>-->

                        <!--<input type="radio" id="type" name="type" value="company">
                        <label for="type">Company Registration</label><br>-->
                        <!--<input type="radio" id="general" name="type" value="general">
                        <label for="general">General</label><br>-->


                        <div class="mb-3">
                            <label for="nameID" class="form-label">Name</label>
                            <input type="text" id="nameID" name="name" class="form-control" placeholder="Name"
                                   required="">
                        </div>

                        <div>Responsible User</div>
                        <input type="hidden" name="users" id="userID">
                        <input id="searchUser" class="form-control mr-sm-2" type="search" autocomplete="off"
                               placeholder="Search" name="s" aria-label="Search">
                        <div id="searchResultsUser" style=" display:none;   position: absolute;
background: white;padding: 10px;
list-style: none;">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="relatedCompany" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Relation</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="personID" class="form-control" value="{{ $person->id }}">

                        <label for="companies">Companies/Persons</label>

                        <input type="hidden" name="companies" id="companyID">
                        <input type="hidden" name="relatedType" id="relatedType">
                        <input id="searchCompany" class="form-control mr-sm-2" type="search" autocomplete="off"
                               placeholder="Search" name="s" aria-label="Search">
                        <div id="searchResultsCompany" style=" display:none;   position: absolute;
background: white;padding: 10px;
list-style: none;">
                        </div>
                        <br>
                        <p>Relation:</p>
                        <input class="personCompanyRelation" type="checkbox" id="boardmember" name="boardmember" value="Board Member">
                        <label for="boardmember">Board Member</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="shareholder" name="shareholder" value="Shareholder">
                        <label for="shareholder">Shareholder</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="agent" name="agent" value="Agent">
                        <label for="agent">Agent</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="agentrepresentative" name="agentrepresentative" value="Agent representative">
                        <label for="agentrepresentative">Agent representative</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="ubo" name="ubo" value="UBO">
                        <label for="ubo">UBO</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="procura" name="procura" value="Procura">
                        <label for="procura">Procura</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="acp" name="acp" value="Authorised contact person">
                        <label for="acp">Authorised contact person</label>  <input id="authorised_person_deadline" type="text"><br>
                        <input class="personCompanyRelation" type="checkbox" id="auditor" name="auditor" value="Auditor">
                        <label for="auditor">Auditor</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="client" name="client" value="Client">
                        <label for="client">Client</label><br>
                        <input class="personCompanyRelation" type="checkbox" id="maincontact" name="maincontact" value="Main contact">
                        <label for="maincontact">Main contact</label><br>

                        <label for="other">Other</label>
                        <input class="personCompanyRelationOther" type="text" id="otherRelation" name="other">

                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNote" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Note</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="personID" class="form-control" value="{{ $person->id }}">

                        <label for="noteContent">Content</label>
                        <textarea id="noteContent" name="noteContent" rows="4" cols="50"></textarea>

                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- KYC Modal -->
    <div class="modal fade" id="addKycModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add KYC Monitoring Info</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kycForm">
                    <div class="modal-body">
                        <input type="hidden" id="kycPersonId" value="{{ $person->id }}">
                        <input type="hidden" id="kycableType" value="App\Models\Person">

                        <div class="mb-3">
                            <label for="kycStartDate" class="form-label">Monitoring date</label>
                            <input type="text" id="kycStartDate" name="start_date" class="form-control" placeholder="dd.mm.yyyy">
                        </div>

                        <div class="mb-3">
                            <label for="kycEndDate" class="form-label">Next monitoring date</label>
                            <input type="text" id="kycEndDate" name="end_date" class="form-control" placeholder="dd.mm.yyyy">
                        </div>

                        <div class="mb-3">
                            <label for="kycRisk" class="form-label">Risk</label>
                            <select id="kycRisk" name="risk" class="form-control">
                                <option value="">Select Risk Level</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kycDocuments" class="form-label">Documents</label>
                            <input type="text" id="kycDocuments" name="documents" class="form-control" placeholder="List of documents">
                        </div>

                        <div class="mb-3">
                            <label for="kycComments" class="form-label">Comments</label>
                            <textarea id="kycComments" name="comments" class="form-control" rows="4" placeholder="KYC comments"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="kycResponsibleUser" class="form-label">Responsible User</label>
                            <input type="hidden" name="responsible_user_id" id="kycResponsibleUserId">
                            <input id="kycResponsibleUser" class="form-control" type="search" autocomplete="off" placeholder="Search for responsible user" aria-label="Search">
                            <div id="kycResponsibleUserResults" style="display:none; position: absolute; background: white; padding: 10px; list-style: none; z-index: 1000;"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="closeKycModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="saveKycRecord">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit KYC Modal -->
    <div class="modal fade" id="editKycModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit KYC Monitoring Info</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKycForm">
                    <div class="modal-body">
                        <input type="hidden" id="editKycId">
                        <input type="hidden" id="editKycPersonId" value="{{ $person->id }}">
                        <input type="hidden" id="editKycableType" value="App\Models\Person">

                        <div class="mb-3">
                            <label for="editKycStartDate" class="form-label">Start Date</label>
                            <input type="text" id="editKycStartDate" name="start_date" class="form-control" placeholder="dd.mm.yyyy">
                        </div>

                        <div class="mb-3">
                            <label for="editKycEndDate" class="form-label">End Date</label>
                            <input type="text" id="editKycEndDate" name="end_date" class="form-control" placeholder="dd.mm.yyyy">
                        </div>

                        <div class="mb-3">
                            <label for="editKycRisk" class="form-label">Risk</label>
                            <select id="editKycRisk" name="risk" class="form-control">
                                <option value="">Select Risk Level</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editKycDocuments" class="form-label">Documents</label>
                            <input type="text" id="editKycDocuments" name="documents" class="form-control" placeholder="List of documents">
                        </div>

                        <div class="mb-3">
                            <label for="editKycComments" class="form-label">Comments</label>
                            <textarea id="editKycComments" name="comments" class="form-control" rows="4" placeholder="KYC comments"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editKycResponsibleUser" class="form-label">Responsible User</label>
                            <input type="hidden" name="responsible_user_id" id="editKycResponsibleUserId">
                            <input id="editKycResponsibleUser" class="form-control" type="search" autocomplete="off" placeholder="Search for responsible user" aria-label="Search">
                            <div id="editKycResponsibleUserResults" style="display:none; position: absolute; background: white; padding: 10px; list-style: none; z-index: 1000;"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="closeEditKycModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="updateKycRecord">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    if (window.initPersonShow) {
        window.initPersonShow({
            entityId: {{ $person->id }},
            kycStoreRoute: '{{ route('kyc.store') }}',
            kycUpdateRoute: '/kyc/update',
            userSearchRoute: '{{ route('autoCompleteModalUser') }}',
            taxResidencyUpdateRoute: '/taxresidency',
            taxResidencyDeleteRoute: '/taxresidency',
            updateRoute: '{{ route('persons.update', $person->id) }}'
        });
    }
</script>

@endsection
