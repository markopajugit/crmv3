@extends('layouts.app')

@section('content')
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
    top: 400px;">Uploading files...</div>
    <script>
        $(document).ready(function () {

            //Change Company name
            var h1 = $('h1').html();
            var dob = $('h5').html();

            $('h1').on('click', 'i', function () {
                $('h5').empty();
                $(this).parent().html(`
                    <form action="{{ route('persons.update',$person->id) }}" method="POST">@csrf @method('PUT')
                    <i class="fa-solid fa-building"></i>
                    <input type="text" name="name" value="{{ $person->name }}" style="font-size:26px;"><br>
                    <input type="text" id="date_of_birth" name="date_of_birth" value="{{ $person->date_of_birth }}" style="font-size:18px;">
                    <button style="margin-right: 5px;" class="cancelEdit btn">Cancel</button>
                    <button type="submit" class="saveEdit btn">Save</button></form>
                `);

                $( "#date_of_birth" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+0",
                    dateFormat: "dd.mm.yy",
                    constrainInput: false
                });
            });

            $('h1').on('click', '.btn.cancelEdit', function () {
                $('h1').html(h1);
                $('h5').html(dob);
            });

        });
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

    <script type="text/javascript">

        $( "#authorised_person_deadline" ).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            dateFormat: "dd.mm.yy",
            constrainInput: false
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#riskEdit .fa-pen-to-square').on('click', function(){
            var companyRiskValue = $(this).parent().siblings('.currentCompanyRisk').children('span').html();
            $(this).parent().siblings('.currentCompanyRisk').children('span').html(`
                <select name="riskOptions" id="riskOptions">
                  <option value="1">Low</option>
                  <option value="2">Medium</option>
                  <option value="3">High</option>
                </select>
            `);
            $(this).hide();
            $(this).siblings('.fa-check').show();
        });

        $('#riskEdit .fa-check').on('click', function(){
            var risk = $('#riskOptions').val();

            $.ajax({
                type: 'POST',
                url: '/person/risk/update',
                data: {person_id: {{$person->id}}, risk_level: risk},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('#showRiskHistory').on('click', function(){
            $('.riskHistoryRows').show();
            $('.currentCompanyRisk').hide();
            $('#riskEdit .fa-pen-to-square').hide();
            $('#riskEdit .fa-check').hide();
            $(this).hide();
        });

        $(document).click(function (event) {
            var $target = $(event.target);
            if (!$target.closest('#searchResultsCompany').length &&
                $('#searchResultsCompany').is(":visible")) {
                $('#searchResultsCompany').hide();
            }
        });

        $('#searchCompany').keyup(function () {
            console.log('keyup');
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('autoCompleteModal') }}",
                    method: "get",
                    data: {s: query, _token: _token, category: 'all'},
                    success: function (data) {
                        console.log('success');
                        console.log(data);
                        $('#searchResultsCompany').fadeIn();
                        $('#searchResultsCompany').html(data);
                    }
                });
            }
        });

        $('#phoneRow .fa-plus').on('click', function(){
            $(this).parent().parent().parent().append('<tr id="newEntityContactRow"><td style="border-top: 0px!important;padding:0;"><input type="text" id="newEntityContactValue" placeholder="Phone"><br><input type="text" id="newEntityContactNote" placeholder="notes"></td><td style="border-top: 0px!important;"><i class="fa-solid fa-check"></i></td></tr>');
            $(this).hide();
        });

        $('#emailRow .fa-plus').on('click', function(){
            $(this).parent().parent().parent().append('<tr id="newEntityContactRow"><td style="border-top: 0px!important;padding:0;"><input type="text" id="newEntityContactValue" placeholder="E-mail"><br><input type="text" id="newEntityContactNote" placeholder="notes"></td><td style="border-top: 0px!important;"><i class="fa-solid fa-check"></i></td></tr>');
            $(this).hide();
        });

        $('#addressRow .fa-plus').on('click', function(){
            $(this).parent().parent().parent().append(`
                <tr id="newEntityAddressRow">
                <td style="border-top: 0px!important;padding:0;">
                                <input type="text" id="insertedAddressStreet" placeholder="Street Address"><br>
                                <input type="text" id="insertedAddressCity" placeholder="City"><br>
                                <input type="text" id="insertedAddressZip" placeholder="ZIP"><br>
                                <select id="insertedAddressDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
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
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </td><td style="border-top: 0px!important;padding:0;">
                    <i class="fa-solid fa-check" id="saveAddressNew"></i>
                </td>`);
            $(this).hide();
        });

        $('#phoneRow .fa-trash').on('click', function(e){
            var contactid = $(this).data('contactid');
            e.preventDefault();
            if (window.confirm("Remove Person Phone?")) {
                $.ajax({
                    url: "/entitycontact/delete/" + contactid,
                    method: "POST",
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        });

        $('#emailRow .fa-trash').on('click', function(e){
            var contactid = $(this).data('contactid');
            e.preventDefault();
            if (window.confirm("Remove Person Email?")) {
                $.ajax({
                    url: "/entitycontact/delete/" + contactid,
                    method: "POST",
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        });

        $('#addressRow .fa-trash').on('click', function(e){
            var contactid = $(this).data('contactid');
            e.preventDefault();
            if (window.confirm("Remove Company Address?")) {
                $.ajax({
                    url: "/entityaddress/delete/" + contactid,
                    method: "POST",
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        });

        $('#phoneRow').on('click', '#newEntityContactRow .fa-check', function(){
            var newPhoneValue = $('#newEntityContactValue').val();
            var newPhoneNote = $('#newEntityContactValue').val();

            $.ajax({
                url: "/entitycontact/new/{{$person->id}}",
                method: "POST",
                data: {value: newPhoneValue, type: 'phone', entity: 'person', note: newPhoneNote},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#emailRow').on('click', '#newEntityContactRow .fa-check', function(){
            var newEmailValue = $('#newEntityContactValue').val();
            var newEmailNote = $('#newEntityContactNote').val();

            $.ajax({
                url: "/entitycontact/new/{{$person->id}}",
                method: "POST",
                data: {value: newEmailValue, type: 'email', entity: 'person', note : newEmailNote},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#addressRow').on('click', '#newEntityAddressRow .fa-check', function(){
            var street = $('#insertedAddressStreet').val();
            var city = $('#insertedAddressCity').val();
            var zip = $('#insertedAddressZip').val();
            var country = $('#insertedAddressDropdown').val();


            $.ajax({
                url: "/entityaddress/new/{{$person->id}}",
                method: "POST",
                data: {company_id: {{$person->id}}, street: street, city: city, zip: zip, country: country, entity: 'person'},
            success: function (data) {
                window.location.reload();
            }
        });
        });

        $('#phoneRow .fa-pen-to-square').on('click', function(){
            var contactid = $(this).parent().siblings('.contactPhone').data('contactid');
            var contactValue = $(this).parent().siblings('.contactPhone').children('.contactPhoneVal').html();
            var contactNote = $(this).parent().siblings('.contactPhone').children('.contactPhoneNote').html();
            if (typeof contactNote === "undefined") {
                var contactNote = '';
            }

            $(this).hide()
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.contactPhone').html(`
                <input type="text" id="updatedPhone" name="updatedPhone" value="`+contactValue+`" data-contactid="`+contactid+`">
                <input type="text" id="updatedPhoneNote" name="updatedPhoneNote" placeholder="Notes" value="`+contactNote+`">
            `);
        });

        $('#taxResidencyRow .fa-pen-to-square').on('click', function(){
            var taxResidencyValue = $(this).parent().siblings('.taxResidency').children('.taxResidencyVal').html();
            taxResidencyValue = $.trim(taxResidencyValue);
            $(this).hide()
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.taxResidency').html(`
                <tr>
                <td style="border-top: 0px!important;padding:0;">
                                <select id="taxResidencyDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </td><td style="border-top: 0px!important;padding:0;">
                </td>`);

            $("#taxResidencyDropdown").val(taxResidencyValue);
        });

        $('#emailRow .fa-pen-to-square').on('click', function(){
            var contactid = $(this).parent().siblings('.contactEmail').data('contactid');
            var contactValue = $(this).parent().siblings('.contactEmail').children('.contactEmailVal').html();
            var contactNote = $(this).parent().siblings('.contactEmail').children('.contactEmailNote').html();
            if (typeof contactNote === "undefined") {
                var contactNote = '';
            }

            $(this).hide()
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.contactEmail').html(`
                <input type="text" id="updatedEmail" name="updatedEmail" value="`+contactValue+`" data-contactid="`+contactid+`" data-oldemail="`+contactValue+`">
                <input type="text" id="updatedEmailNote" name="updatedEmailNote" placeholder="Notes" value="`+contactNote+`">
            `);
        });

        $('#addressRow .fa-pen-to-square').on('click', function(){
            var contactid = $(this).parent().siblings('.contactAddress').data('contactid');
            var sibling = $(this).parent().siblings('.contactAddress');
            var street = sibling.find('.addressStreet').html();
            var city = sibling.find('.addressCity').html();
            var zip = sibling.find('.addressZip').html();
            var country = sibling.find('.addressCountry').html();
            var addressNote = sibling.find('.addressNote').html();
            if (typeof addressNote === "undefined") {
                var addressNote = '';
            }

            $(this).hide();
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.contactAddress').html(`
                <tr id="newEntityAddressRow">
                <td style="border-top: 0px!important;padding:0;" data-contactid="`+contactid+`">
                                <input type="text" id="editAddressStreet" value="`+street+`"><br>
                                <input type="text" id="editAddressCity" value="`+city+`"><br>
                                <input type="text" id="editAddressZip" value="`+zip+`"><br>
                                <select id="editAddressDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                <input type="text" id="editAddressNote" placeholder="Notes" value="`+addressNote+`"><br>
                            </td><td style="border-top: 0px!important;padding:0;">
                </td>`);

            $("#editAddressDropdown").val(country);
        });

        $('#phoneRow .fa-check').on('click', function(){
            var updatedPhoneValue = $('#updatedPhone').val();
            var updatedPhoneNote = $('#updatedPhoneNote').val();
            var contactid = $('#updatedPhone').data('contactid');

            $.ajax({
                url: "/entitycontact/update/"+contactid,
                method: "POST",
                data: {value: updatedPhoneValue, person_id: {{$person->id}}, type: 'phone', entity: 'person', note: updatedPhoneNote},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#taxResidencyRow .fa-check').on('click', function(){
            var updatedTaxResidencyValue = $('#taxResidencyDropdown').val();
            console.log(updatedTaxResidencyValue);

            $.ajax({
                url: "/entitycontact/update/0",
                method: "POST",
                data: {value: updatedTaxResidencyValue, person_id: {{$person->id}}, type: 'taxResidency', entity: 'person'},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#emailRow .fa-check').on('click', function(){
            var updatedEmailValue = $('#updatedEmail').val();
            var email_note = $('#updatedEmailNote').val();
            var contactid = $('#updatedEmail').data('contactid');
            var oldemail = $('#updatedEmail').data('oldemail');

            $.ajax({
                url: "/entitycontact/update/"+contactid,
                method: "POST",
                data: {value: updatedEmailValue, person_id: {{$person->id}}, type: 'email', entity: 'person', note: email_note, old_email: oldemail},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#addressRow .fa-check').on('click', function(){
            var contactid = $(this).closest('tr').find('.contactAddress').data('contactid');
            var street = $('#editAddressStreet').val();
            var city = $('#editAddressCity').val();
            var zip = $('#editAddressZip').val();
            var country = $('#editAddressDropdown').val();
            var address_note = $('#editAddressNote').val();

            $.ajax({
                url: "/entityaddress/update/"+contactid,
                method: "POST",
                data: {street: street, city: city, zip: zip, country: country, entity: 'person', address_note : address_note, person_id: {{$person->id}}},
            success: function (data) {
                window.location.reload();
            }
        });
        });

        $('.generateArchiveNumber').on('click', function(){
            var fileId = $(this).data('fileid');
            $.ajax({
                type: 'POST',
                url: "/file/archivenr/generate/"+fileId,
                data: {generate: true},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editArchiveNumber').on('click', function(){
            $(this).hide();

            $(this).siblings('.saveArchiveNumber').show();

            var fileId = $(this).data('fileid');
            console.log(fileId);
            var currentArchiveNumber = $('#orderArchiveNumber-'+fileId).html();
            console.log(currentArchiveNumber);

            $('#orderArchiveNumber-'+fileId).html(`
                <input type="text" id="updatedArchiveNumber" name="updatedArchiveNumber" value="`+currentArchiveNumber+`">
            `);
        });

        $('.panel-body').on('click', '.saveArchiveNumber', function(){

            var fileId = $(this).data('fileid');

            var updatedArchiveNumber = $('#updatedArchiveNumber').val();

            console.log(fileId);
            console.log(updatedArchiveNumber);

            $.ajax({
                type: 'POST',
                url: "/file/archivenr/"+fileId,
                data: {archive_nr: updatedArchiveNumber},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $(document).on('click', '#searchResultsCompany li', function () {
            $('#searchCompany').val($(this).text());

            console.log($(this));

            if($(this).data('type') == 'persons'){
                $('#relatedType').val('persons');
            }
            if($(this).data('type') == 'companies'){
                $('#relatedType').val('companies');
            }

            $('#companyID').val($(this).data('id'));
            $('#searchResultsCompany').fadeOut();
        });


        $('.editDetails').on('click', function(){
            $(this).hide();

            $('#addressRow').html(`
                <td style="width:50%"><strong>Address:</strong></td>
                <td>
                                <input type="text" id="insertedAddressStreet" placeholder="Street Address" value="{{ $person->address_street }}"><br>
                                <input type="text" id="insertedAddressCity" placeholder="City" value="{{ $person->address_city }}"><br>
                                <input type="text" id="insertedAddressZip" placeholder="ZIP" value="{{ $person->address_zip }}"><br>
                                <select id="insertedAddressDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </td><td><i id="saveAddress" class="fa-solid fa-check"></i></td><td></td>`);

            $("#insertedAddressDropdown").val("{{ $person->address_dropdown }}");
        });

        $('#idRow .fa-pen-to-square').on('click', function(){
            $('#idRow').html(`
                <td><b>ID code:</b></td>
                <td>
                    <input type="text" id="updatedIdCode" value="{{$person->id_code}}">
                    <select id="updatedIdCodeCountry">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                    <input type="text" id="updatedIdCodeEst" value="{{$person->id_code_est}}"> (Estonia)
                </td>
                <td>
                    <i class="fa-solid fa-check"></i>
                </td>
            `);

            $("#updatedIdCodeCountry").val("{{ $person->country }}");
        });

        var documentsCount = $('.regularDocuments table tr').length;
        var VOdocumentsCount = $('.VOdocuments table tr').length;

        $('#documentsCount').html('('+documentsCount+')');
        $('#VOdocumentsCount').html('('+VOdocumentsCount+')');

        $('#idRow').on('click', '.fa-check', function(){
            var updatedIdCode = $('#updatedIdCode').val();
            var updatedCountry = $('#updatedIdCodeCountry').val();
            var updatedIdCodeEst = $('#updatedIdCodeEst').val();

            $.ajax({
                url: '{{ route('persons.update',$person->id) }}',
                method: "PUT",
                data: {id_code: updatedIdCode, country: updatedCountry, id_code_est: updatedIdCodeEst},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('.panel-details').on('click', '#saveAddress', function(){
            var address_street = $("#insertedAddressStreet").val();
            var address_city = $("#insertedAddressCity").val();
            var address_zip = $("#insertedAddressZip").val();
            var address_dropdown = $("#insertedAddressDropdown").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {address_street: address_street, address_zip: address_zip, address_city: address_city, address_dropdown: address_dropdown},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });


        $('.editNotes').on('click', function(){
            $(this).html('<i class="fa-solid fa-pen-to-square"></i>Save');
            $(this).hide();
            $('.saveNotes').show();
            var currentNotes = $('.panel-notes .panel-body').html();
            $('.panel-notes .panel-body').html('<textarea cols="60" rows="5" id="notes" name="notes">' + $.trim(currentNotes) + '</textarea>');

        });

        $('.panel-notes').on('click', '.panel-heading__button .saveNotes', function(){
            var notesVal = $('#notes').val();
            var person_id = $("#personID").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {person_id: person_id, notes: notesVal},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });


        /*USER*/

        $(document).click(function (event) {
            var $target = $(event.target);
            if (!$target.closest('#searchResultsUser').length &&
                $('#searchResultsUser').is(":visible")) {
                $('#searchResultsUser').hide();
            }
        });

        $('#searchUser').keyup(function () {
            //console.log('keyup');
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('autoCompleteModalUser') }}",
                    method: "get",
                    data: {s: query, _token: _token, category: 'users'},
                    success: function (data) {
                        //console.log('success');
                        //console.log(data);
                        $('#searchResultsUser').fadeIn();
                        $('#searchResultsUser').html(data);
                    }
                });
            }
        });

        $(document).on('click', '#searchResultsUser li', function () {
            $('#searchUser').val($(this).text());
            $('#userID').val($(this).data('id'));
            $('#searchResultsUser').fadeOut();
        });


        $("#addOrderModal .btn-submit").click(function () {
            var person_id = $("#personID").val();
            var user_id = $("#userID").val();
            var name = $("#nameID").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('orders.store') }}",
                data: {person_id: person_id, responsible_user_id: user_id, name: name},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });

        });


        $("#relatedCompany .btn-submit").click(function () {
            var company_id = $("#companyID").val();
            var person_id = $("#personID").val();
            var type = $('#relatedType').val();

            var typetosend = 'companytoperson';
            if(type === 'persons'){
                var typetosend = 'persontoperson';
            }
            if(type === 'companies'){
                var typetosend = 'companytoperson';
            }

            var relationArray = [];

            $('.personCompanyRelation').each(function() {
                if($(this).is(':checked')){
                    relationArray.push($(this).val());
                }
            });

            if( $('#otherRelation').val() ) {
                relationArray.push($('#otherRelation').val());
            }

            var relation = relationArray.toString();

            $.ajax({
                type: 'POST',
                url: "/companies/" + company_id + "/client",
                data: {company_id: company_id, person_id: person_id, relation: relation, type: typetosend},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });


        $("#addNote .btn-submit").click(function () {
            var person_id = $("#personID").val();
            var title = $("#noteTitle").val();
            var content = $("#noteContent").val();

            $.ajax({
                type: 'POST',
                url: "/notes/person/" + person_id,
                data: {person_id: person_id, title: title, content: content},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });


        //Delete relatedPerson
        $('.relatedPersons').on('click', '.fa-xmark', function (e) {
            e.preventDefault();
            if (window.confirm("Remove Relation?")) {
                var companyID = $(this).data().companyid;
                var personID = $(this).data().personid;
                var relation = $(this).data().relation;

                var type = 'company';

                if(personID){
                    console.log('see on person');
                    var type = 'person';
                    var companyID = personID;
                }

                $.ajax({
                    type: 'POST',
                    url: "/companies/" + companyID + "/client/delete",
                    data: {company_id: companyID, person_id: {{ $person->id }}, relation: relation, type: type },
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            window.location.reload()
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            }
        });

        $('.note').on('click', '.fa-trash', function (e) {
            e.preventDefault();
            if (window.confirm("Remove Note?")) {
                var noteID = $(this).data().noteid;
                $.ajax({
                    type: 'POST',
                    url: "/notes/delete/" + noteID,
                    data: {person_id: {{ $person->id }} },
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            window.location.reload()
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            }
        });

        $('.note').on('click', '.fa-pen-to-square', function (e) {

            e.preventDefault();

            var content = $(this).siblings('.noteContent').data('content');

            $(this).siblings('.noteContent').html('<textarea id="noteContentNew" name="noteContent" rows="4" cols="50">'+content+'</textarea>');

            $(this).hide();
            $(this).siblings('.fa-check').show();

        });

        $('.note').on('click', '.fa-check', function (e) {
            e.preventDefault();
            //if (window.confirm("Remove Note?")) {

            var noteID = $(this).data().noteid;
            var content = $("#noteContentNew").val();
            console.log("content: "+content);

            $.ajax({
                type: 'POST',
                url: "/notes/update/" + noteID,
                data: {person_id: {{ $person->id }}, content: content },
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }

            });
            //}
        });

        //Edit relatedPerson
        $('.relatedPersons').on('click', '.fa-pen-to-square', function (e) {
            console.log($(this).parent().parent());

            e.preventDefault();

                var companyID = $(this).data().companyid;
                var companyName = $(this).data().companyname;


                var personID = $(this).data().personid;
                var relation = $(this).data().relation;

                var type = 'company';

                if(personID){
                    var personName = $(this).data().personname;
                    var companyID = personID;
                    $(this).parent().parent().html(`

                        <td style="width:50%"><i class="fa-solid fa-building" style="margin-right: 5px;"></i><a
                                href="/persons/`+companyID+`">`+personName+`</a></td>
                        <td><input type="text" id="newrelation" value="`+relation+`"</td>
                        <td>
                            <i class="fa-solid fa-check"
                               style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                               data-personID="`+companyID+`" data-relation="`+relation+`"></i>
                        </td>
                        <td>
                            <i class="fa-solid fa-xmark"
                               style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                               data-personID="`+companyID+`" data-relation="`+relation+`"></i>
                        </td>

                    `);
                } else {
                    $(this).parent().parent().html(`

                <td style="width:50%"><i class="fa-solid fa-building" style="margin-right: 5px;"></i><a
                        href="/companies/`+companyID+`">`+companyName+`</a></td>
                <td><input type="text" id="newrelation" value="`+relation+`"</td>
                <td>
                    <i class="fa-solid fa-check"
                       style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                       data-companyID="`+companyID+`" data-relation="`+relation+`"></i>
                </td>
                <td>
                    <i class="fa-solid fa-xmark"
                       style="vertical-align: middle; margin-left: 10px;font-size: 20px;"
                       data-companyID="`+companyID+`" data-relation="`+relation+`"></i>
                </td>

            `);
                }


        });

        //Delete relatedPerson
        $('.relatedPersons').on('click', '.fa-check', function (e) {

            e.preventDefault();
            //if (window.confirm("Remove Related Company?")) {

                var companyID = $(this).data().companyid;
                var relation = $(this).data().relation;
                var newrelation = $('#newrelation').val();

                var personID = $(this).data().personid;

                var type = 'company';

                if(personID){
                    console.log('see on person');
                    var type = 'person';
                    var companyID = personID;
                }

                $.ajax({
                    type: 'POST',
                    url: "/companies/" + companyID + "/client/update",
                    data: {company_id: companyID, person_id: {{ $person->id }}, relation: newrelation, type: type },
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            window.location.reload()
                        } else {
                            printErrorMsg(data.error);
                        }
                    }

                });
            //}

        });


        $("#files").change(function() {
            filename = this.files[0].name;
            $('#filesButton').after('<span style="margin-left: 10px;">'+filename+'</span>')
            console.log(filename);
        });

        $('#deletePerson').on('click', function(e){
            e.preventDefault();
            @if($person->orders->isEmpty() && $person->companies->isEmpty())
                if (window.confirm("Delete Person?")) {
                    var personId = $(this).data('personid');
                    $.ajax({
                        type: 'DELETE',
                        url: "/persons/"+personId,
                        success: function (data) {
                            if ($.isEmptyObject(data.error)) {
                                window.location.replace("/persons");
                            } else {
                                printErrorMsg(data.error);
                            }
                        }
                    });
                }
            @else
                alert('Make sure person has no related companies or orders!');
            @endif
        });

        $('#birthplaceCountryRow .fa-pen-to-square').on('click', function(){
            var birthplaceCountryValue = $(this).parent().siblings('.birthplaceCountry').children('.birthplaceCountryVal').html();
            birthplaceCountryValue = $.trim(birthplaceCountryValue);
            $(this).hide()
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.birthplaceCountry').html(`
                <tr>
                <td style="border-top: 0px!important;padding:0;">
                                <select id="birthplaceCountryDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </td><td style="border-top: 0px!important;padding:0;">
                </td>`);

            $("#birthplaceCountryDropdown").val(birthplaceCountryValue);
        });

        $('#birthplaceCountryRow .fa-check').on('click', function(){
            var updatedBirthplaceCountryValue = $('#birthplaceCountryDropdown').val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {birthplace_country: updatedBirthplaceCountryValue},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editBirthplaceCity').on('click', function() {
            $(this).hide();

            $('#birthplaceCityRow').html(`<td><b>Birthplace City:</b></td><td><input type="text" value="{{ $person->birthplace_city }}" id="updatedBirthplaceCity"></td><td><i class="fa-solid fa-check" id="saveBirthplaceCity"></i></td>`);
        });

        $('.panel-details').on('click', '#saveBirthplaceCity', function(){
            var updatedBirthplaceCity = $("#updatedBirthplaceCity").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {birthplace_city: updatedBirthplaceCity},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('#citizenshipRow .fa-pen-to-square').on('click', function(){
            var citizenshipValue = $(this).parent().siblings('.citizenship').children('.citizenshipVal').html();
            citizenshipValue = $.trim(citizenshipValue);
            $(this).hide()
            $(this).siblings('.fa-check').show();

            $(this).parent().siblings('.citizenship').html(`
                <tr>
                <td style="border-top: 0px!important;padding:0;">
                                <select id="citizenshipDropdown">
                                    <option value="">country</option>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Aland Islands">Aland Islands</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antarctica">Antarctica</option>
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
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
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Bouvet Island">Bouvet Island</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of the Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curacao">Curacao</option>
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
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Territories">French Southern Territories</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guernsey">Guernsey</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jersey">Jersey</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                    <option value="Kosovo">Kosovo</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macao">Macao</option>
                                    <option value="Macedonia, the Former Yugoslav Republic of">Macedonia, the Former Yugoslav Republic of</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherlands">Netherlands</option>
                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau">Palau</option>
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Pitcairn">Pitcairn</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russian Federation">Russian Federation</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="Saint Barthelemy">Saint Barthelemy</option>
                                    <option value="Saint Helena">Saint Helena</option>
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia">Saint Lucia</option>
                                    <option value="Saint Martin">Saint Martin</option>
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Sint Maarten">Sint Maarten</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                    <option value="South Sudan">South Sudan</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Timor-Leste">Timor-Leste</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                    <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                    <option value="Western Sahara">Western Sahara</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </td><td style="border-top: 0px!important;padding:0;">
                </td>`);

            $("#citizenshipDropdown").val(citizenshipValue);
        });

        $('#citizenshipRow .fa-check').on('click', function(){
            var updatedCitizenshipValue = $('#citizenshipDropdown').val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {citizenship: updatedCitizenshipValue},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editPep').on('click', function() {
            $(this).hide();

            $('#pepRow').html(`<td><b>PEP (Politically Exposed Person):</b></td><td><select id="updatedPep"><option value="">Select</option><option value="0">No</option><option value="1">Yes</option></select></td><td><i class="fa-solid fa-check" id="savePep"></i></td>`);
            
            $("#updatedPep").val("{{ $person->pep }}");
        });

        $('.panel-details').on('click', '#savePep', function(){
            var updatedPep = $("#updatedPep").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('persons.update',$person->id) }}",
                data: {pep: updatedPep},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        // Tax Residency Management
        $('#addTaxResidencyBtn').on('click', function(){
            $('#newTaxResidencyRow').show();
            $(this).hide();
            $('#noTaxResidencies').hide();
        });

        $('#cancelNewTaxResidency').on('click', function(){
            $('#newTaxResidencyRow').hide();
            $('#addTaxResidencyBtn').show();
            
            // Reset form
            $('#newTaxResidencyCountry').val('');
            $('#newTaxResidencyValidFrom').val('');
            $('#newTaxResidencyValidTo').val('');
            $('#newTaxResidencyPrimary').prop('checked', false);
            $('#newTaxResidencyNotes').val('');
            
            // Show "no tax residencies" message if no items exist
            if ($('.tax-residency-item').length === 0) {
                $('#noTaxResidencies').show();
            }
        });

        // Initialize date pickers for tax residency dates
        $('#newTaxResidencyValidFrom, #newTaxResidencyValidTo').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-50:+50",
            dateFormat: "dd.mm.yy",
            constrainInput: false
        });

        $('#saveNewTaxResidency').on('click', function(){
            var country = $('#newTaxResidencyCountry').val();
            var validFrom = $('#newTaxResidencyValidFrom').val();
            var validTo = $('#newTaxResidencyValidTo').val();
            var isPrimary = $('#newTaxResidencyPrimary').is(':checked') ? 1 : 0;
            var notes = $('#newTaxResidencyNotes').val();

            if (!country) {
                alert('Please select a country');
                return;
            }

            $.ajax({
                url: "/taxresidency/person/{{$person->id}}",
                method: "POST",
                data: {
                    country: country,
                    valid_from: validFrom,
                    valid_to: validTo,
                    is_primary: isPrimary,
                    notes: notes
                },
                success: function (data) {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error adding tax residency');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        });

        $('.edit-tax-residency').on('click', function(){
            var taxResidencyId = $(this).data('id');
            var container = $(this).closest('.tax-residency-item');
            var country = container.find('.tax-residency-country').text().trim();
            var isPrimary = container.find('.badge-primary').length > 0;
            
            // Get dates from the display
            var dateText = container.find('small').first().text();
            var validFrom = '';
            var validTo = '';
            
            if (dateText.includes('From:')) {
                var fromMatch = dateText.match(/From:\s*([^\s]+)/);
                if (fromMatch) validFrom = fromMatch[1];
            }
            if (dateText.includes('To:')) {
                var toMatch = dateText.match(/To:\s*([^\s]+)/);
                if (toMatch) validTo = toMatch[1];
            }
            
            // Get notes
            var notes = '';
            var notesElement = container.find('small').last();
            if (notesElement.length && !notesElement.text().includes('From:') && !notesElement.text().includes('To:')) {
                notes = notesElement.text().trim();
            }

            container.html(`
                <table width="100%" style="margin: 0;">
                    <tr>
                        <td style="border: none; padding: 2px;">
                            <select class="edit-country-dropdown" style="width: 100%; margin-bottom: 5px;">
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
                                <option value="Serbia and Montenegro">Serbia and Montenegro</option>
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
                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-Leste">Timor-Leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tokelau">Tokelau</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
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

            // Set the current country value
            container.find('.edit-country-dropdown').val(country);
            
            // Initialize date pickers for edit form
            container.find('.edit-valid-from, .edit-valid-to').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-50:+50",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
        });

        // Handle save edit tax residency
        $(document).on('click', '.save-edit-tax-residency', function(){
            var taxResidencyId = $(this).data('id');
            var container = $(this).closest('.tax-residency-item');
            var country = container.find('.edit-country-dropdown').val();
            var validFrom = container.find('.edit-valid-from').val();
            var validTo = container.find('.edit-valid-to').val();
            var isPrimary = container.find('.edit-primary').is(':checked');
            var notes = container.find('.edit-notes').val();

            if (!country) {
                alert('Please select a country');
                return;
            }

            $.ajax({
                url: "/taxresidency/" + taxResidencyId,
                method: "PUT",
                data: {
                    country: country,
                    valid_from: validFrom,
                    valid_to: validTo,
                    is_primary: isPrimary,
                    notes: notes
                },
                success: function (data) {
                    if (data.success) {
                        window.location.reload();
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
        $(document).on('click', '.cancel-edit-tax-residency', function(){
            window.location.reload();
        });

        // Handle delete tax residency
        $('.delete-tax-residency').on('click', function(){
            if (confirm('Are you sure you want to delete this tax residency?')) {
                var taxResidencyId = $(this).data('id');
                
                $.ajax({
                    url: "/taxresidency/" + taxResidencyId,
                    method: "DELETE",
                    success: function (data) {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error deleting tax residency');
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            }
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>

    <script type="text/javascript">

        Dropzone.options.dropzoneForm = {
            autoProcessQueue : true,

            init:function(){
                var submitButton = document.querySelector("#submit-all");
                myDropzone = this;

                submitButton.addEventListener('click', function(){
                    //myDropzone.processQueue();
                    window.location.reload();
                });

                this.on('sending', function(file, xhr, formData){
                    $('body').css('opacity', '0.5');
                    $('#loading').show();
                    formData.append('personID', {{ $person->id }});
                });

                this.on("complete", function(){
                    if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
                    {
                        //var _this = this;
                        //_this.removeAllFiles();
                        window.location.reload();
                    }

                    //window.location.reload();
                });

            }

        };

        $(document).on('click', '.deleteDocument', function(e){
            e.preventDefault();

            if (window.confirm("Delete Document?")) {
                var fileName = $(this).data('filename');

                console.log(fileName);
                $.ajax({
                    type: 'DELETE',
                    url:"/file/delete/person/{{ $person->id }}/"+fileName,
                    success: function (data) {
                        if ($.isEmptyObject(data.error)) {
                            window.location.reload();
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });
            }
        });

    </script>

    <script>
        // KYC Functionality
        $(document).ready(function () {
            // Initialize date pickers for KYC dates
            $("#kycStartDate, #kycEndDate, #editKycStartDate, #editKycEndDate").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-10:+10",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });

            // User search for responsible user (Add form)
            $('#kycResponsibleUser').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: "{{ route('autoCompleteModalUser') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#kycResponsibleUserResults').html(data).show();
                        }
                    });
                } else {
                    $('#kycResponsibleUserResults').hide();
                }
            });

            // User search for responsible user (Edit form)
            $('#editKycResponsibleUser').on('keyup', function() {
                var query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: "{{ route('autoCompleteModalUser') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#editKycResponsibleUserResults').html(data).show();
                        }
                    });
                } else {
                    $('#editKycResponsibleUserResults').hide();
                }
            });

            // Handle user selection (Add form)
            $(document).on('click', '#kycResponsibleUserResults li', function() {
                $('#kycResponsibleUser').val($(this).text());
                $('#kycResponsibleUserId').val($(this).data('id'));
                $('#kycResponsibleUserResults').hide();
            });

            // Handle user selection (Edit form)
            $(document).on('click', '#editKycResponsibleUserResults li', function() {
                $('#editKycResponsibleUser').val($(this).text());
                $('#editKycResponsibleUserId').val($(this).data('id'));
                $('#editKycResponsibleUserResults').hide();
            });

            // Save KYC record
            $('#saveKycRecord').on('click', function() {
                var entityId = $('#kycCompanyId').val() || $('#kycPersonId').val();
                var kycableType = $('#kycableType').val();
                
                $.ajax({
                    url: "{{ route('kyc.store') }}",
                    method: 'POST',
                    data: {
                        kycable_type: kycableType,
                        kycable_id: entityId,
                        responsible_user_id: $('#kycResponsibleUserId').val(),
                        start_date: $('#kycStartDate').val(),
                        end_date: $('#kycEndDate').val(),
                        risk: $('#kycRisk').val(),
                        documents: $('#kycDocuments').val(),
                        comments: $('#kycComments').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Error saving KYC record: ' + xhr.responseText);
                    }
                });
            });

            // Edit KYC record
            $(document).on('click', '.kyc-edit', function() {
                var kycId = $(this).data('kycid');
                
                // Get current values from the KYC record display
                var kycRecord = $(this).closest('.kyc-record');
                var kycDetails = kycRecord.find('.kyc-details');
                
                // Extract current values
                var currentData = {
                    id: kycId,
                    start_date: '',
                    end_date: '',
                    risk: '',
                    documents: '',
                    comments: '',
                    responsible_user_name: ''
                };

                // Parse the display text to extract values
                var detailsText = kycDetails.html();
                
                // Extract period dates
                var periodMatch = detailsText.match(/Period:<\/strong>\s*([^<]*)/);
                if (periodMatch) {
                    var dates = periodMatch[1].split(' - ');
                    currentData.start_date = dates[0] ? dates[0].trim() : '';
                    currentData.end_date = dates[1] ? dates[1].trim() : '';
                }
                
                // Extract risk
                var riskMatch = detailsText.match(/Risk:<\/strong>\s*([^<]*)/);
                if (riskMatch) {
                    currentData.risk = riskMatch[1].trim();
                }
                
                // Extract documents
                var documentsMatch = detailsText.match(/Documents:<\/strong>\s*([^<]*)/);
                if (documentsMatch) {
                    currentData.documents = documentsMatch[1].trim();
                }
                
                // Extract comments
                var commentsMatch = detailsText.match(/Comments:<\/strong>\s*(.*?)(?=<strong>|$)/s);
                if (commentsMatch) {
                    currentData.comments = commentsMatch[1].replace(/<br\s*\/?>/gi, '\n').trim();
                }
                
                // Extract responsible user name
                var responsibleUserText = kycRecord.find('b').first().text();
                var userMatch = responsibleUserText.match(/^([^(]+)/);
                if (userMatch) {
                    currentData.responsible_user_name = userMatch[1].trim();
                }

                // Populate edit form
                $('#editKycId').val(kycId);
                $('#editKycStartDate').val(currentData.start_date);
                $('#editKycEndDate').val(currentData.end_date);
                $('#editKycRisk').val(currentData.risk);
                $('#editKycDocuments').val(currentData.documents);
                $('#editKycComments').val(currentData.comments);
                $('#editKycResponsibleUser').val(currentData.responsible_user_name);
                
                // Show edit modal
                $('#editKycModal').modal('show');
            });

            // Update KYC record
            $('#updateKycRecord').on('click', function() {
                var kycId = $('#editKycId').val();
                
                $.ajax({
                    url: '/kyc/update/' + kycId,
                    method: 'PUT',
                    data: {
                        responsible_user_id: $('#editKycResponsibleUserId').val(),
                        start_date: $('#editKycStartDate').val(),
                        end_date: $('#editKycEndDate').val(),
                        risk: $('#editKycRisk').val(),
                        documents: $('#editKycDocuments').val(),
                        comments: $('#editKycComments').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editKycModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Error updating KYC record: ' + xhr.responseText);
                    }
                });
            });

            // Delete KYC record
            $(document).on('click', '.kyc-delete', function() {
                if (confirm('Are you sure you want to delete this KYC record?')) {
                    var kycId = $(this).data('kycid');
                    $.ajax({
                        url: '/kyc/delete/' + kycId,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting KYC record: ' + xhr.responseText);
                        }
                    });
                }
            });

            // Clear form when modals close
            $('#addKycModal').on('hidden.coreui.modal', function() {
                $('#kycForm')[0].reset();
                $('#kycResponsibleUserId').val('');
                $('#kycResponsibleUserResults').hide();
            });

            $('#editKycModal').on('hidden.coreui.modal', function() {
                $('#editKycForm')[0].reset();
                $('#editKycResponsibleUserId').val('');
                $('#editKycResponsibleUserResults').hide();
            });
        });
    </script>
@endsection
