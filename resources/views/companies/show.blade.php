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
    top: 400px;">Uploading files...
    <div class="progress">
        <div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress>
            <span class="progress-text"></span>
        </div>
    </div>
</div>
    @if($errors->any())
        {{ implode('', $errors->all(':message')) }}
    @endif
    @php
        if(!$company->registry_code){
            $company->registry_code = "-";
        }
    @endphp

    <script>
        $(document).ready(function () {

            //Change Company name
            var h1 = $('h1').html();
            var regCode = $('h5').html();

            $('h1').on('click', 'i.fa-pen-to-square', function () {

                $(this).parent().html(`
                    <form action="{{ route('companies.update',$company->id) }}" method="POST">@csrf @method('PUT')
                    <i class="fa-solid fa-building"></i><input type="text" name="number" placeholder="Company number" value="{{ $company->number }}" style="font-size:24px;">
                    <input type="text" name="name" value="{{ $company->name }}" style="font-size:26px;"><br>
                    <span style="font-size:18px;">Reg:</span> <input type="text" name="registry_code" value="{{ $company->registry_code }}" style="font-size:18px;">
                    <button style="margin-right: 5px;" class="cancelEdit btn">Cancel</button>
                    <button type="submit" class="saveEdit btn">Save</button></form>
                `);

                $('h5').hide();
            });

            $('h1').on('click', '.btn.cancelEdit', function () {
                $('h1').html(h1);
                $('h5').html(regCode);
            });


            //Change relatedPersons
            $('.panel-heading').on('click', '.editRelatedPersons', function () {
                $(this).html('<i class="fa-solid fa-pen-to-square"></i>Save');
                $('.relatedPersons').hide();
                $('.relatedPersonsEdit').show();

                $(this).addClass('saveRelatedPersons');
            });

            $('.panel-heading').on('click', '.saveRelatedPersons', function () {
                console.log('save relatedpersons');
                $(this).html('<i class="fa-solid fa-pen-to-square"></i>Edit Related Person');

                $('.relatedPersons').show();
                $('.relatedPersonsEdit').hide();

                $(this).removeClass('saveRelatedPersons');

                //$('#updateRelatedPersons').submit();

            });


            //Delete relatedPerson
            $('.relatedPersons').on('click', '.fa-xmark.relatedPerson', function (e) {

                e.preventDefault();
                if (window.confirm("Remove Related Person?")) {

                    var personID = $(this).data().personid;
                    var relation = $(this).data().relation;

                    $.ajax({
                        type: 'POST',
                        url: "/companies/" + {{ $company->id }} + "/client/delete",
                        data: {company_id: {{ $company->id }}, person_id: personID, relation: relation},
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


            //Delete relatedCompany
            $('.relatedPersons').on('click', '.fa-xmark.relatedCompany', function (e) {

                e.preventDefault();
                if (window.confirm("Remove Related Company?")) {

                    var relatedCompanyID = $(this).data().relatedcompanyid;
                    var relation = $(this).data().relation;

                    $.ajax({
                        type: 'POST',
                        url: "/companies/" + {{ $company->id }} + "/company/delete",
                        data: {company_id: {{ $company->id }}, relatedCompany_id: relatedCompanyID, relation: relation},
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

        });
    </script>
    <style>
        td .fa-plus {
            color:green;
        }

        .progress-bar {
            width: 0%;
            height: 20px;
            background-color: #4CAF50;
        }
    </style>
    <a style="float:right; background:darkred!important;" target="_blank" class="btn btn-primary" data-companyid="{{$company->id}}" id="deleteCompany"><i class="fa-solid fa-trash"></i>Delete Company</a>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1><i class="fa-solid fa-building"></i><i>{{ $company->number }}</i> {{ $company->name }}<i class="fa-solid fa-pen-to-square"
                                                                           style="cursor: pointer;vertical-align: middle; margin-left: 10px;font-size: 20px;"></i>
                @if($company->deleted)
                    (Deleted)
                @endif
                @if($company->registry_code)
                    <a target="_blank" href="https://ariregister.rik.ee/est/company/{{$company->registry_code}}"><i class="fa-solid fa-share-from-square fa-2xs"></i></a>
                @endif
            </h1>

            <h5>Reg: {{ $company->registry_code }}
                @php
                    $showbutton = true;
                @endphp
                @foreach ($company->persons as $person)
                    @if($person->pivot->relation == 'Main Contact')
                        @php
                            $showbutton = false;
                        @endphp
                         - <i class="fa-solid fa-user" style="margin-right: 5px;"></i><a
                            href="/persons/{{$person->id}}">{{$person->name}}</a> {{$person->pivot->selected_email}}
                    @endif
                @endforeach</h5>

            @if($showbutton)
            <a target="_blank" class="btn btn-primary" data-coreui-toggle="modal"
               data-coreui-target="#addMainContact"><i class="fa-solid fa-plus"></i>Add main contact person</a>
            @endif
            <!--<a target="_blank" class="btn btn-primary" data-companyid="{{$company->id}}" id=""><i class="fa-solid fa-trash"></i>Change company status to Deleted</a>-->
        </div>
    </div>

    <div class="modal fade" id="addMainContact" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add main contact</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <label for="persons">Persons</label>

                        <input type="hidden" name="persons" id="personID">
                        <input id="searchPerson" class="form-control mr-sm-2" type="search" autocomplete="off"
                               placeholder="Search" name="s" aria-label="Search">
                        <div id="searchResultsPerson" style=" display:none;   position: absolute;
background: white;padding: 10px;
list-style: none;">
                        </div>

                        <div id="emails"></div>
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
                                data-coreui-target="#relatedPerson">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add Relation
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table relatedPersons">

                        @foreach ($company->persons as $person)
                            @if($person->pivot->relation == 'Main Contact')
                                <tr>
                                    <td style="width:50%"><i class="fa-solid fa-user maincontactperson" style="margin-right: 5px;"></i><a
                                                href="/persons/{{$person->id}}">{{$person->name}}</a></td>
                                    <td>
                                        <b>Main Contact</b>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-xmark relatedPerson"
                                           style="vertical-align: middle; margin-left: 10px;font-size: 20px;cursor: pointer;"
                                           data-personID="{{$person->id}}" data-relation="{{ $person->pivot->relation }}"></i>
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        @foreach ($relatedCompanies as $relatedCompany)
                        <tr>
                            <td style="width:50%"><i class="fa-solid fa-building relatedcompany" style="margin-right: 5px;"></i><a
                                        href="/companies/{{$relatedCompany->id}}">{{$relatedCompany->name}}</a></td>
                            <td>
                                {{$relatedCompany->relation}} @if($relatedCompany->relation == 'Authorised contact person' && !empty($relatedCompany->contact_deadline)) ({{$relatedCompany->contact_deadline}}) @endif
                            </td>
                            <td>
                                <i class="fa-solid fa-xmark relatedCompany"
                                   style="vertical-align: middle; margin-left: 10px;font-size: 20px;cursor: pointer;"
                                   data-relatedcompanyid="{{$relatedCompany->id}}" data-relation="{{$relatedCompany->relation}}"></i>
                            </td>
                        </tr>
                        @endforeach

                        @foreach ($company->persons as $person)
                            @if($person->pivot->relation != 'Main Contact')
                                <tr>
                                    <td style="width:50%"><i class="fa-solid fa-user relatedperson" style="margin-right: 5px;"></i><a
                                            href="/persons/{{$person->id}}">{{$person->name}}</a></td>
                                    <td>
                                        {{ $person->pivot->relation }} @if($person->pivot->relation == 'Authorised contact person' && !empty($person->pivot->contact_deadline)) ({{$person->pivot->contact_deadline}}) @endif
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-xmark relatedPerson"
                                           style="vertical-align: middle; margin-left: 10px;font-size: 20px;cursor: pointer;"
                                           data-personID="{{$person->id}}" data-relation="{{ $person->pivot->relation }}"></i>
                                    </td>
                                </tr>
                            @endif
                        @endforeach



                    </table>

                    <table class="table relatedPersonsEdit" style="display:none;">
                        <form id="updateRelatedPersons" action="{{ route('companies.update',$company->id) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="companyID" value="{{$company->id}}">
                            @foreach ($company->persons as $person)
                                <tr>
                                    <td style="width:50%"><i class="fa-solid fa-user" style="margin-right: 5px;"></i><a
                                            href="/persons/{{$person->id}}">{{$person->name}}</a></td>
                                    <td>
                                        <select name="relation[]" id="relation">
                                            <option value="shareholder">Shareholder</option>
                                            <option value="agent">Agent</option>
                                        </select>
                                        <input type="hidden" name="persons[]" value="{{$person->id}}">

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
                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addOrderModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add Order
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        @foreach ($company->orders as $order)
                            <tr>
                                <td><i class="fa-solid fa-file"></i>
                                    <a href="/orders/{{$order->id}}"><b>{{$order->number}}</b> {{$order->name}}</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">

                        <!--<button type="button" class="btn editDetails">
                            <i class="fa-solid fa-pen-to-square"></i>Edit
                        </button>-->

                        <button type="button" class="btn saveDetails" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr id="dateRow">
                            <td style="width:50%"><strong>Registration date:</strong></td>
                            <td id="currentRegistrationDate">{{ $company->registration_date }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editDate"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr id="vatRow">
                            <td style="width:50%"><strong>VAT No:</strong></td>
                            <td id="currentVat">{{ $company->vat }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editVat"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <!--<tr id="addressRow">
                            <td style="width:50%"><strong>Address:</strong></td>
                            <td id="currentAddress">{{ $company->address_street }}@if($company->address_city), {{ $company->address_city }}@endif <br> {{ $company->address_zip }}@if($company->address_dropdown), {{ $company->address_dropdown }}@endif</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editAddress"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>-->
                        <tr id="regCountryRow">
                            <td style="width:50%"><strong>Registration country:</strong></td>
                            <td id="currentRegCountry">{{ $company->registration_country }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editRegCountry"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr id="addressRow">
                            <td style="width:50%"><strong>Address:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactAddress" data-contactid="0"><span class="addressStreet">{{ $company->address_street }}</span>@if($company->address_city), <span class="addressCity">{{ $company->address_city }}</span>@endif <br>
                                            <span class="addressZip">{{ $company->address_zip }}</span>@if($company->address_dropdown), <span class="addressCountry">{{ $company->address_dropdown }}</span>@endif
                                            @if($company->address_note)<br> <i class="fa-regular fa-comment"></i> <span class="addressNote">{{ $company->address_note }}</span>@endif
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                    </tr>
                                    @foreach($company->getAddresses as $address)
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
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactEmail" data-contactid="0"><span class="contactEmailVal">{{ $company->email }}</span>
                                            @if($company->email_note)<br> <i class="fa-regular fa-comment"></i> <span class="contactEmailNote">{{ $company->email_note }}</span>@endif</td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                    </tr>
                                    @foreach($company->getContacts as $contact)
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
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="contactPhone" data-contactid="0"><span class="contactPhoneVal">{{ $company->phone }}</span>
                                            @if($company->phone_note)<br> <i class="fa-regular fa-comment"></i> <span class="contactPhoneNote">{{ $company->phone_note }}</span>@endif
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-plus"></i></td>
                                    </tr>
                                    @foreach($company->getContacts as $contact)
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
                        <tr id="riskRow">
                            <td style="width:50%"><strong>Risk:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tr>
                                        <td style="min-width: 200px; border-top: 0!important; padding:0;" class="currentCompanyRisk">
                                            @if($company->getCurrentRisk)
                                                @if($company->getCurrentRisk->risk_level == 1)
                                                    <span style="color: green;">LOW</span>
                                                @elseif($company->getCurrentRisk->risk_level == 2)
                                                    <span style="color: orange;">MEDIUM</span>
                                                @elseif($company->getCurrentRisk->risk_level == 3)
                                                    <span style="color: red;">HIGH</span>
                                                @endif
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;" id="riskEdit"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <!--<td style="border-top: 0!important;padding:0;text-align: center; cursor: pointer;" id="showRiskHistory">Show history</td>-->
                                    </tr>
                                    @foreach($company->getRisksHistory as $risk)
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
                        <tr id="taxResidencyRow">
                            <td style="width:50%"><strong>Tax Residency:</strong></td>
                            <td colspan="3">
                                <table width="100%">
                                    <tbody><tr>
                                        <td style="min-width:200px;border-top: 0!important;padding:0;" class="taxResidency"><span class="taxResidencyVal">@if($company->tax_residency) {{$company->tax_residency}} @endif</span>
                                        </td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                        <td style="border-top: 0!important;padding:0;text-align: center;"></td>
                                    </tr>
                                    </tbody></table>
                            </td>
                        </tr>
                        <tr id="kycRow">
                            <td style="width:50%"><strong>Service provision period:</strong></td>
                            <td id="currentKYC">Start: {{ $company->kyc_start }}<br>End: {{ $company->kyc_end }}<br>Reason: {{ $company->kyc_reason }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editKyc"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr id="activityCodeRow">
                            <td style="width:50%"><strong>Activity Code:</strong></td>
                            <td id="currentActivityCode">{{ $company->activity_code }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editActivityCode"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                        </tr>
                        <tr id="activityCodeDescriptionRow">
                            <td style="width:50%"><strong>Activity Code Description:</strong></td>
                            <td id="currentActivityCodeDescription">{{ $company->activity_code_description }}</td>
                            <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square editActivityCodeDescription"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
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
                    @foreach($company->kycs()->latest()->get() as $kyc)
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
                    @if($company->kycs()->count() == 0)
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
                    @if($company->notes)
                        <div>Vana note: {{$company->notes}}</div>
                    @endif
                    @foreach($company->getNotes as $note)
                        <div style="border-bottom: 1px solid black; padding: 5px 0;" class="note"><b>{{$note->responsible_user($note->user_id)->name}} ({{$note->created_at}})</b><i class="fa-solid fa-pen-to-square" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-check" style="display:none;vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-trash" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><br>
                            <div class="noteContent" data-content="{{$note->content}}">{!! nl2br(e($note->content)) !!}</div></div>
                    @endforeach
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
                        @foreach ($company->files as $file)
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
                                <td><a href="/file/company/{{ $company->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                <td><a href="/file/download/company/{{ $company->id }}/{{$file->name}}" target="_blank">DOWNLOAD</a></td>
                                <td><a class="deleteDocument" href="/file/delete/company/{{ $company->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a></td>
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
                        @foreach ($company->files as $file)
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
                                    <td><a href="/file/company/{{ $company->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                    <td><a href="/file/download/company/{{ $company->id }}/{{$file->name}}"target="_blank">DOWNLOAD</a></td>
                                    <td><a class="deleteDocument" href="/file/delete/company/{{ $company->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a></td>
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
                        <input type="hidden" id="companyID" class="form-control" value="{{ $company->id }}">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="mb-3">
                            <label for="companyName" class="form-label">Company</label>
                            <input type="text" id="companyName" class="form-control" value="{{ $company->name }}"
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

    <div class="modal fade" id="addNote" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Note</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="companyID" class="form-control" value="{{ $company->id }}">

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

    <div class="modal fade" id="relatedPerson" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Relation</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="companyID" class="form-control" value="{{ $company->id }}">

                        <label for="persons">Person or Company</label>

                        <input type="hidden" name="persons" id="selectedPersonRelation">
                        <input type="hidden" name="companies" id="selectedCompanyRelation">
                        <input id="searchPerson2" class="form-control mr-sm-2" type="search" autocomplete="off"
                               placeholder="Search" name="s" aria-label="Search">
                        <div id="searchResultsPerson2" style=" display:none;   position: absolute;
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
                        <!--<input class="personCompanyRelation" type="checkbox" id="maincontact" name="maincontact" value="Main Contact">
                        <label for="maincontact">Main Contact</label><br>-->

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
                        <input type="hidden" id="kycCompanyId" value="{{ $company->id }}">
                        <input type="hidden" id="kycableType" value="App\Models\Company">

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
                        <input type="hidden" id="editKycCompanyId" value="{{ $company->id }}">
                        <input type="hidden" id="editKycableType" value="App\Models\Company">

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
        // Wait for jQuery to be loaded
        (function() {
            function initCompanyShowMain() {
                if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
                    setTimeout(initCompanyShowMain, 50);
                    return;
                }

                var $ = window.jQuery;

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

        var documentsCount = $('.regularDocuments table tr').length;
        var VOdocumentsCount = $('.VOdocuments table tr').length;

        $('#documentsCount').html('('+documentsCount+')');
        $('#VOdocumentsCount').html('('+VOdocumentsCount+')');

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
                url: '/company/risk/update',
                data: {company_id: {{$company->id}}, risk_level: risk},
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

        $('#taxResidencyRow .fa-check').on('click', function(){
            var updatedTaxResidencyValue = $('#taxResidencyDropdown').val();
            console.log(updatedTaxResidencyValue);

            $.ajax({
                url: "/entitycontact/update/0",
                method: "POST",
                data: {value: updatedTaxResidencyValue, company_id: {{$company->id}}, type: 'taxResidency', entity: 'company'},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $("#staticBackdrop .btn-submit").click(function () {

            var company_id = $("#companyID").val();
            var name = $("#nameID").val();
            var description = $("#descriptionID").val();
            var notes = $("#notesID").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('orders.store') }}",
                data: {company_id: company_id, name: name, description: description, notes: notes},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
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

        $("#relatedPerson .btn-submit").click(function () {

            var company_id = $("#companyID").val();
            var person_id = $("#personID").val();
            var authorised_person_deadline = $('#authorised_person_deadline').val();
            console.log (authorised_person_deadline);

            if($('#selectedCompanyRelation').val()){
                var type = 'company';
                var entity_id = $('#selectedCompanyRelation').val();
            }
            if($('#selectedPersonRelation').val()){
                var type = 'person';
                var entity_id = $('#selectedPersonRelation').val();
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
                data: {company_id: company_id, entity_id: entity_id, relation: relation, type: type, contact_deadline: authorised_person_deadline},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $("#addMainContact .btn-submit").click(function () {

            var person_id = $("#personID").val();

            var chosenEmail = $('input[name=emails]:checked', '#emails').val()

            //console.log(chosenEmail);

            $.ajax({
                type: 'POST',
                url: "/companies/{{$company->id}}/client",
                data: {company_id: {{$company->id}}, entity_id: person_id, relation: 'Main Contact', selected_email: chosenEmail, type: 'person', test : 'test123'},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }

        $(document).click(function (event) {
            var $target = $(event.target);
            if (!$target.closest('#searchResultsPerson').length &&
                $('#searchResultsPerson').is(":visible")) {
                $('#searchResultsPerson').hide();
            }
        });

        $('#searchPerson').keyup(function () {
            console.log('keyup');
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('autoCompleteModal') }}",
                    method: "get",
                    data: {s: query, _token: _token, category: 'persons'},
                    success: function (data) {
                        console.log('success');
                        console.log(data);
                        $('#searchResultsPerson').fadeIn();
                        $('#searchResultsPerson').html(data);
                    }
                });
            }
        });

        $('#searchPerson2').keyup(function () {
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
                        $('#searchResultsPerson2').fadeIn();
                        $('#searchResultsPerson2').html(data);
                    }
                });
            }
        });

        $(document).on('click', '#searchResultsPerson li', function () {
            var person_id = $(this).data('id');
            $('#searchPerson').val($(this).text());
            $('#personID').val(person_id);
            $('#searchResultsPerson').fadeOut();

            //Siia peab ajaxiga küsima emaili ja andma valiku
            $.ajax({
                url: "/entitycontact/get/",
                method: "POST",
                data: {entity_id: person_id, type: 'email', entity: 'person'},
                success: function (data) {
                    $('#emails').html(data);
                }
            });
        });


        $(document).on('click', '#searchResultsPerson2 li', function () {
            var person_id = $(this).data('id');
            $('#searchPerson2').val($(this).text());
            if($(this).data('type') == 'companies'){
                $('#selectedCompanyRelation').val(person_id);
            }
            if($(this).data('type') == 'persons'){
                $('#selectedPersonRelation').val(person_id);
            }

            $('#searchResultsPerson2').fadeOut();

            //Siia peab ajaxiga küsima emaili ja andma valiku
            $.ajax({
                url: "/entitycontact/get/",
                method: "POST",
                data: {entity_id: person_id, type: 'email', entity: 'company'},
                success: function (data) {
                    $('#emails').html(data);
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
            //console.log('Clicked add');
            $('#searchUser').val($(this).text());
            $('#userID').val($(this).data('id'));
            $('#searchResultsUser').fadeOut();
        });

        $("#addOrderModal .btn-submit").click(function () {

            console.log('Clicked add');

            var company_id = $("#companyID").val();
            var user_id = $("#userID").val();
            var name = $("#nameID").val();
            //var _token = $('input[name="_token"]').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('orders.store') }}",
                data: {company_id: company_id, responsible_user_id: user_id, name: name, description: '', notes: ''},
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

        $('.editNotes').on('click', function(){

            $(this).html('<i class="fa-solid fa-pen-to-square"></i>Save');

            $(this).hide();

            $('.saveNotes').show();

            var currentNotes = $('.panel-notes .panel-body').html();

            $('.panel-notes .panel-body').html('<textarea cols="60" rows="5" id="notes" name="notes">' + $.trim(currentNotes) + '</textarea>');

        });

        $('.panel-notes').on('click', '.panel-heading__button .saveNotes', function(){
            var notesVal = $('#notes').val();

            var company_id = $("#companyID").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {company_id: company_id, notes: notesVal},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editDate').on('click', function() {
            $(this).hide();

            $('#dateRow').html(`<td><b>Registration date:</b></td><td><input type="text" value="{{$company->registration_date}}" id="updatedDate"></td><td><i class="fa-solid fa-check" id="saveDate"></i></td>`);

            $( "#updatedDate" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
        });

        $('.panel-details').on('click', '#saveDate', function(){
            var updatedDate = $("#updatedDate").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {registration_date: updatedDate},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editVat').on('click', function() {
            $(this).hide();

            $('#vatRow').html(`<td><b>VAT No:</b></td><td><input type="text" value="{{$company->vat}}" id="updatedVat"></td><td><i class="fa-solid fa-check" id="saveVat"></i></td>`);
        });

        $('.editRegCountry').on('click', function() {
            $(this).hide();

            var currentCountry = "{{$company->registration_country}}";
            var countryOptions = `
                <option value="">Select country</option>
                <option value="Afghanistan" ${currentCountry === 'Afghanistan' ? 'selected' : ''}>Afghanistan</option>
                <option value="Aland Islands" ${currentCountry === 'Aland Islands' ? 'selected' : ''}>Aland Islands</option>
                <option value="Albania" ${currentCountry === 'Albania' ? 'selected' : ''}>Albania</option>
                <option value="Algeria" ${currentCountry === 'Algeria' ? 'selected' : ''}>Algeria</option>
                <option value="American Samoa" ${currentCountry === 'American Samoa' ? 'selected' : ''}>American Samoa</option>
                <option value="Andorra" ${currentCountry === 'Andorra' ? 'selected' : ''}>Andorra</option>
                <option value="Angola" ${currentCountry === 'Angola' ? 'selected' : ''}>Angola</option>
                <option value="Anguilla" ${currentCountry === 'Anguilla' ? 'selected' : ''}>Anguilla</option>
                <option value="Antarctica" ${currentCountry === 'Antarctica' ? 'selected' : ''}>Antarctica</option>
                <option value="Antigua and Barbuda" ${currentCountry === 'Antigua and Barbuda' ? 'selected' : ''}>Antigua and Barbuda</option>
                <option value="Argentina" ${currentCountry === 'Argentina' ? 'selected' : ''}>Argentina</option>
                <option value="Armenia" ${currentCountry === 'Armenia' ? 'selected' : ''}>Armenia</option>
                <option value="Aruba" ${currentCountry === 'Aruba' ? 'selected' : ''}>Aruba</option>
                <option value="Australia" ${currentCountry === 'Australia' ? 'selected' : ''}>Australia</option>
                <option value="Austria" ${currentCountry === 'Austria' ? 'selected' : ''}>Austria</option>
                <option value="Azerbaijan" ${currentCountry === 'Azerbaijan' ? 'selected' : ''}>Azerbaijan</option>
                <option value="Bahamas" ${currentCountry === 'Bahamas' ? 'selected' : ''}>Bahamas</option>
                <option value="Bahrain" ${currentCountry === 'Bahrain' ? 'selected' : ''}>Bahrain</option>
                <option value="Bangladesh" ${currentCountry === 'Bangladesh' ? 'selected' : ''}>Bangladesh</option>
                <option value="Barbados" ${currentCountry === 'Barbados' ? 'selected' : ''}>Barbados</option>
                <option value="Belarus" ${currentCountry === 'Belarus' ? 'selected' : ''}>Belarus</option>
                <option value="Belgium" ${currentCountry === 'Belgium' ? 'selected' : ''}>Belgium</option>
                <option value="Belize" ${currentCountry === 'Belize' ? 'selected' : ''}>Belize</option>
                <option value="Benin" ${currentCountry === 'Benin' ? 'selected' : ''}>Benin</option>
                <option value="Bermuda" ${currentCountry === 'Bermuda' ? 'selected' : ''}>Bermuda</option>
                <option value="Bhutan" ${currentCountry === 'Bhutan' ? 'selected' : ''}>Bhutan</option>
                <option value="Bolivia" ${currentCountry === 'Bolivia' ? 'selected' : ''}>Bolivia</option>
                <option value="Bonaire, Sint Eustatius and Saba" ${currentCountry === 'Bonaire, Sint Eustatius and Saba' ? 'selected' : ''}>Bonaire, Sint Eustatius and Saba</option>
                <option value="Bosnia and Herzegovina" ${currentCountry === 'Bosnia and Herzegovina' ? 'selected' : ''}>Bosnia and Herzegovina</option>
                <option value="Botswana" ${currentCountry === 'Botswana' ? 'selected' : ''}>Botswana</option>
                <option value="Bouvet Island" ${currentCountry === 'Bouvet Island' ? 'selected' : ''}>Bouvet Island</option>
                <option value="Brazil" ${currentCountry === 'Brazil' ? 'selected' : ''}>Brazil</option>
                <option value="British Indian Ocean Territory" ${currentCountry === 'British Indian Ocean Territory' ? 'selected' : ''}>British Indian Ocean Territory</option>
                <option value="Brunei Darussalam" ${currentCountry === 'Brunei Darussalam' ? 'selected' : ''}>Brunei Darussalam</option>
                <option value="Bulgaria" ${currentCountry === 'Bulgaria' ? 'selected' : ''}>Bulgaria</option>
                <option value="Burkina Faso" ${currentCountry === 'Burkina Faso' ? 'selected' : ''}>Burkina Faso</option>
                <option value="Burundi" ${currentCountry === 'Burundi' ? 'selected' : ''}>Burundi</option>
                <option value="Cambodia" ${currentCountry === 'Cambodia' ? 'selected' : ''}>Cambodia</option>
                <option value="Cameroon" ${currentCountry === 'Cameroon' ? 'selected' : ''}>Cameroon</option>
                <option value="Canada" ${currentCountry === 'Canada' ? 'selected' : ''}>Canada</option>
                <option value="Cape Verde" ${currentCountry === 'Cape Verde' ? 'selected' : ''}>Cape Verde</option>
                <option value="Cayman Islands" ${currentCountry === 'Cayman Islands' ? 'selected' : ''}>Cayman Islands</option>
                <option value="Central African Republic" ${currentCountry === 'Central African Republic' ? 'selected' : ''}>Central African Republic</option>
                <option value="Chad" ${currentCountry === 'Chad' ? 'selected' : ''}>Chad</option>
                <option value="Chile" ${currentCountry === 'Chile' ? 'selected' : ''}>Chile</option>
                <option value="China" ${currentCountry === 'China' ? 'selected' : ''}>China</option>
                <option value="Christmas Island" ${currentCountry === 'Christmas Island' ? 'selected' : ''}>Christmas Island</option>
                <option value="Cocos (Keeling) Islands" ${currentCountry === 'Cocos (Keeling) Islands' ? 'selected' : ''}>Cocos (Keeling) Islands</option>
                <option value="Colombia" ${currentCountry === 'Colombia' ? 'selected' : ''}>Colombia</option>
                <option value="Comoros" ${currentCountry === 'Comoros' ? 'selected' : ''}>Comoros</option>
                <option value="Congo" ${currentCountry === 'Congo' ? 'selected' : ''}>Congo</option>
                <option value="Congo, Democratic Republic of the Congo" ${currentCountry === 'Congo, Democratic Republic of the Congo' ? 'selected' : ''}>Congo, Democratic Republic of the Congo</option>
                <option value="Cook Islands" ${currentCountry === 'Cook Islands' ? 'selected' : ''}>Cook Islands</option>
                <option value="Costa Rica" ${currentCountry === 'Costa Rica' ? 'selected' : ''}>Costa Rica</option>
                <option value="Cote D'Ivoire" ${currentCountry === 'Cote D\'Ivoire' ? 'selected' : ''}>Cote D'Ivoire</option>
                <option value="Croatia" ${currentCountry === 'Croatia' ? 'selected' : ''}>Croatia</option>
                <option value="Cuba" ${currentCountry === 'Cuba' ? 'selected' : ''}>Cuba</option>
                <option value="Curacao" ${currentCountry === 'Curacao' ? 'selected' : ''}>Curacao</option>
                <option value="Cyprus" ${currentCountry === 'Cyprus' ? 'selected' : ''}>Cyprus</option>
                <option value="Czech Republic" ${currentCountry === 'Czech Republic' ? 'selected' : ''}>Czech Republic</option>
                <option value="Denmark" ${currentCountry === 'Denmark' ? 'selected' : ''}>Denmark</option>
                <option value="Djibouti" ${currentCountry === 'Djibouti' ? 'selected' : ''}>Djibouti</option>
                <option value="Dominica" ${currentCountry === 'Dominica' ? 'selected' : ''}>Dominica</option>
                <option value="Dominican Republic" ${currentCountry === 'Dominican Republic' ? 'selected' : ''}>Dominican Republic</option>
                <option value="Ecuador" ${currentCountry === 'Ecuador' ? 'selected' : ''}>Ecuador</option>
                <option value="Egypt" ${currentCountry === 'Egypt' ? 'selected' : ''}>Egypt</option>
                <option value="El Salvador" ${currentCountry === 'El Salvador' ? 'selected' : ''}>El Salvador</option>
                <option value="Equatorial Guinea" ${currentCountry === 'Equatorial Guinea' ? 'selected' : ''}>Equatorial Guinea</option>
                <option value="Eritrea" ${currentCountry === 'Eritrea' ? 'selected' : ''}>Eritrea</option>
                <option value="Estonia" ${currentCountry === 'Estonia' ? 'selected' : ''}>Estonia</option>
                <option value="Ethiopia" ${currentCountry === 'Ethiopia' ? 'selected' : ''}>Ethiopia</option>
                <option value="Falkland Islands (Malvinas)" ${currentCountry === 'Falkland Islands (Malvinas)' ? 'selected' : ''}>Falkland Islands (Malvinas)</option>
                <option value="Faroe Islands" ${currentCountry === 'Faroe Islands' ? 'selected' : ''}>Faroe Islands</option>
                <option value="Fiji" ${currentCountry === 'Fiji' ? 'selected' : ''}>Fiji</option>
                <option value="Finland" ${currentCountry === 'Finland' ? 'selected' : ''}>Finland</option>
                <option value="France" ${currentCountry === 'France' ? 'selected' : ''}>France</option>
                <option value="French Guiana" ${currentCountry === 'French Guiana' ? 'selected' : ''}>French Guiana</option>
                <option value="French Polynesia" ${currentCountry === 'French Polynesia' ? 'selected' : ''}>French Polynesia</option>
                <option value="French Southern Territories" ${currentCountry === 'French Southern Territories' ? 'selected' : ''}>French Southern Territories</option>
                <option value="Gabon" ${currentCountry === 'Gabon' ? 'selected' : ''}>Gabon</option>
                <option value="Gambia" ${currentCountry === 'Gambia' ? 'selected' : ''}>Gambia</option>
                <option value="Georgia" ${currentCountry === 'Georgia' ? 'selected' : ''}>Georgia</option>
                <option value="Germany" ${currentCountry === 'Germany' ? 'selected' : ''}>Germany</option>
                <option value="Ghana" ${currentCountry === 'Ghana' ? 'selected' : ''}>Ghana</option>
                <option value="Gibraltar" ${currentCountry === 'Gibraltar' ? 'selected' : ''}>Gibraltar</option>
                <option value="Greece" ${currentCountry === 'Greece' ? 'selected' : ''}>Greece</option>
                <option value="Greenland" ${currentCountry === 'Greenland' ? 'selected' : ''}>Greenland</option>
                <option value="Grenada" ${currentCountry === 'Grenada' ? 'selected' : ''}>Grenada</option>
                <option value="Guadeloupe" ${currentCountry === 'Guadeloupe' ? 'selected' : ''}>Guadeloupe</option>
                <option value="Guam" ${currentCountry === 'Guam' ? 'selected' : ''}>Guam</option>
                <option value="Guatemala" ${currentCountry === 'Guatemala' ? 'selected' : ''}>Guatemala</option>
                <option value="Guernsey" ${currentCountry === 'Guernsey' ? 'selected' : ''}>Guernsey</option>
                <option value="Guinea" ${currentCountry === 'Guinea' ? 'selected' : ''}>Guinea</option>
                <option value="Guinea-Bissau" ${currentCountry === 'Guinea-Bissau' ? 'selected' : ''}>Guinea-Bissau</option>
                <option value="Guyana" ${currentCountry === 'Guyana' ? 'selected' : ''}>Guyana</option>
                <option value="Haiti" ${currentCountry === 'Haiti' ? 'selected' : ''}>Haiti</option>
                <option value="Heard Island and Mcdonald Islands" ${currentCountry === 'Heard Island and Mcdonald Islands' ? 'selected' : ''}>Heard Island and Mcdonald Islands</option>
                <option value="Holy See (Vatican City State)" ${currentCountry === 'Holy See (Vatican City State)' ? 'selected' : ''}>Holy See (Vatican City State)</option>
                <option value="Honduras" ${currentCountry === 'Honduras' ? 'selected' : ''}>Honduras</option>
                <option value="Hong Kong" ${currentCountry === 'Hong Kong' ? 'selected' : ''}>Hong Kong</option>
                <option value="Hungary" ${currentCountry === 'Hungary' ? 'selected' : ''}>Hungary</option>
                <option value="Iceland" ${currentCountry === 'Iceland' ? 'selected' : ''}>Iceland</option>
                <option value="India" ${currentCountry === 'India' ? 'selected' : ''}>India</option>
                <option value="Indonesia" ${currentCountry === 'Indonesia' ? 'selected' : ''}>Indonesia</option>
                <option value="Iran, Islamic Republic of" ${currentCountry === 'Iran, Islamic Republic of' ? 'selected' : ''}>Iran, Islamic Republic of</option>
                <option value="Iraq" ${currentCountry === 'Iraq' ? 'selected' : ''}>Iraq</option>
                <option value="Ireland" ${currentCountry === 'Ireland' ? 'selected' : ''}>Ireland</option>
                <option value="Isle of Man" ${currentCountry === 'Isle of Man' ? 'selected' : ''}>Isle of Man</option>
                <option value="Israel" ${currentCountry === 'Israel' ? 'selected' : ''}>Israel</option>
                <option value="Italy" ${currentCountry === 'Italy' ? 'selected' : ''}>Italy</option>
                <option value="Jamaica" ${currentCountry === 'Jamaica' ? 'selected' : ''}>Jamaica</option>
                <option value="Japan" ${currentCountry === 'Japan' ? 'selected' : ''}>Japan</option>
                <option value="Jersey" ${currentCountry === 'Jersey' ? 'selected' : ''}>Jersey</option>
                <option value="Jordan" ${currentCountry === 'Jordan' ? 'selected' : ''}>Jordan</option>
                <option value="Kazakhstan" ${currentCountry === 'Kazakhstan' ? 'selected' : ''}>Kazakhstan</option>
                <option value="Kenya" ${currentCountry === 'Kenya' ? 'selected' : ''}>Kenya</option>
                <option value="Kiribati" ${currentCountry === 'Kiribati' ? 'selected' : ''}>Kiribati</option>
                <option value="Korea, Democratic People's Republic of" ${currentCountry === 'Korea, Democratic People\'s Republic of' ? 'selected' : ''}>Korea, Democratic People's Republic of</option>
                <option value="Korea, Republic of" ${currentCountry === 'Korea, Republic of' ? 'selected' : ''}>Korea, Republic of</option>
                <option value="Kosovo" ${currentCountry === 'Kosovo' ? 'selected' : ''}>Kosovo</option>
                <option value="Kuwait" ${currentCountry === 'Kuwait' ? 'selected' : ''}>Kuwait</option>
                <option value="Kyrgyzstan" ${currentCountry === 'Kyrgyzstan' ? 'selected' : ''}>Kyrgyzstan</option>
                <option value="Lao People's Democratic Republic" ${currentCountry === 'Lao People\'s Democratic Republic' ? 'selected' : ''}>Lao People's Democratic Republic</option>
                <option value="Latvia" ${currentCountry === 'Latvia' ? 'selected' : ''}>Latvia</option>
                <option value="Lebanon" ${currentCountry === 'Lebanon' ? 'selected' : ''}>Lebanon</option>
                <option value="Lesotho" ${currentCountry === 'Lesotho' ? 'selected' : ''}>Lesotho</option>
                <option value="Liberia" ${currentCountry === 'Liberia' ? 'selected' : ''}>Liberia</option>
                <option value="Libyan Arab Jamahiriya" ${currentCountry === 'Libyan Arab Jamahiriya' ? 'selected' : ''}>Libyan Arab Jamahiriya</option>
                <option value="Liechtenstein" ${currentCountry === 'Liechtenstein' ? 'selected' : ''}>Liechtenstein</option>
                <option value="Lithuania" ${currentCountry === 'Lithuania' ? 'selected' : ''}>Lithuania</option>
                <option value="Luxembourg" ${currentCountry === 'Luxembourg' ? 'selected' : ''}>Luxembourg</option>
                <option value="Macao" ${currentCountry === 'Macao' ? 'selected' : ''}>Macao</option>
                <option value="Macedonia, the Former Yugoslav Republic of" ${currentCountry === 'Macedonia, the Former Yugoslav Republic of' ? 'selected' : ''}>Macedonia, the Former Yugoslav Republic of</option>
                <option value="Madagascar" ${currentCountry === 'Madagascar' ? 'selected' : ''}>Madagascar</option>
                <option value="Malawi" ${currentCountry === 'Malawi' ? 'selected' : ''}>Malawi</option>
                <option value="Malaysia" ${currentCountry === 'Malaysia' ? 'selected' : ''}>Malaysia</option>
                <option value="Maldives" ${currentCountry === 'Maldives' ? 'selected' : ''}>Maldives</option>
                <option value="Mali" ${currentCountry === 'Mali' ? 'selected' : ''}>Mali</option>
                <option value="Malta" ${currentCountry === 'Malta' ? 'selected' : ''}>Malta</option>
                <option value="Marshall Islands" ${currentCountry === 'Marshall Islands' ? 'selected' : ''}>Marshall Islands</option>
                <option value="Martinique" ${currentCountry === 'Martinique' ? 'selected' : ''}>Martinique</option>
                <option value="Mauritania" ${currentCountry === 'Mauritania' ? 'selected' : ''}>Mauritania</option>
                <option value="Mauritius" ${currentCountry === 'Mauritius' ? 'selected' : ''}>Mauritius</option>
                <option value="Mayotte" ${currentCountry === 'Mayotte' ? 'selected' : ''}>Mayotte</option>
                <option value="Mexico" ${currentCountry === 'Mexico' ? 'selected' : ''}>Mexico</option>
                <option value="Micronesia, Federated States of" ${currentCountry === 'Micronesia, Federated States of' ? 'selected' : ''}>Micronesia, Federated States of</option>
                <option value="Moldova, Republic of" ${currentCountry === 'Moldova, Republic of' ? 'selected' : ''}>Moldova, Republic of</option>
                <option value="Monaco" ${currentCountry === 'Monaco' ? 'selected' : ''}>Monaco</option>
                <option value="Mongolia" ${currentCountry === 'Mongolia' ? 'selected' : ''}>Mongolia</option>
                <option value="Montenegro" ${currentCountry === 'Montenegro' ? 'selected' : ''}>Montenegro</option>
                <option value="Montserrat" ${currentCountry === 'Montserrat' ? 'selected' : ''}>Montserrat</option>
                <option value="Morocco" ${currentCountry === 'Morocco' ? 'selected' : ''}>Morocco</option>
                <option value="Mozambique" ${currentCountry === 'Mozambique' ? 'selected' : ''}>Mozambique</option>
                <option value="Myanmar" ${currentCountry === 'Myanmar' ? 'selected' : ''}>Myanmar</option>
                <option value="Namibia" ${currentCountry === 'Namibia' ? 'selected' : ''}>Namibia</option>
                <option value="Nauru" ${currentCountry === 'Nauru' ? 'selected' : ''}>Nauru</option>
                <option value="Nepal" ${currentCountry === 'Nepal' ? 'selected' : ''}>Nepal</option>
                <option value="Netherlands" ${currentCountry === 'Netherlands' ? 'selected' : ''}>Netherlands</option>
                <option value="Netherlands Antilles" ${currentCountry === 'Netherlands Antilles' ? 'selected' : ''}>Netherlands Antilles</option>
                <option value="New Caledonia" ${currentCountry === 'New Caledonia' ? 'selected' : ''}>New Caledonia</option>
                <option value="New Zealand" ${currentCountry === 'New Zealand' ? 'selected' : ''}>New Zealand</option>
                <option value="Nicaragua" ${currentCountry === 'Nicaragua' ? 'selected' : ''}>Nicaragua</option>
                <option value="Niger" ${currentCountry === 'Niger' ? 'selected' : ''}>Niger</option>
                <option value="Nigeria" ${currentCountry === 'Nigeria' ? 'selected' : ''}>Nigeria</option>
                <option value="Niue" ${currentCountry === 'Niue' ? 'selected' : ''}>Niue</option>
                <option value="Norfolk Island" ${currentCountry === 'Norfolk Island' ? 'selected' : ''}>Norfolk Island</option>
                <option value="Northern Mariana Islands" ${currentCountry === 'Northern Mariana Islands' ? 'selected' : ''}>Northern Mariana Islands</option>
                <option value="Norway" ${currentCountry === 'Norway' ? 'selected' : ''}>Norway</option>
                <option value="Oman" ${currentCountry === 'Oman' ? 'selected' : ''}>Oman</option>
                <option value="Pakistan" ${currentCountry === 'Pakistan' ? 'selected' : ''}>Pakistan</option>
                <option value="Palau" ${currentCountry === 'Palau' ? 'selected' : ''}>Palau</option>
                <option value="Palestinian Territory, Occupied" ${currentCountry === 'Palestinian Territory, Occupied' ? 'selected' : ''}>Palestinian Territory, Occupied</option>
                <option value="Panama" ${currentCountry === 'Panama' ? 'selected' : ''}>Panama</option>
                <option value="Papua New Guinea" ${currentCountry === 'Papua New Guinea' ? 'selected' : ''}>Papua New Guinea</option>
                <option value="Paraguay" ${currentCountry === 'Paraguay' ? 'selected' : ''}>Paraguay</option>
                <option value="Peru" ${currentCountry === 'Peru' ? 'selected' : ''}>Peru</option>
                <option value="Philippines" ${currentCountry === 'Philippines' ? 'selected' : ''}>Philippines</option>
                <option value="Pitcairn" ${currentCountry === 'Pitcairn' ? 'selected' : ''}>Pitcairn</option>
                <option value="Poland" ${currentCountry === 'Poland' ? 'selected' : ''}>Poland</option>
                <option value="Portugal" ${currentCountry === 'Portugal' ? 'selected' : ''}>Portugal</option>
                <option value="Puerto Rico" ${currentCountry === 'Puerto Rico' ? 'selected' : ''}>Puerto Rico</option>
                <option value="Qatar" ${currentCountry === 'Qatar' ? 'selected' : ''}>Qatar</option>
                <option value="Reunion" ${currentCountry === 'Reunion' ? 'selected' : ''}>Reunion</option>
                <option value="Romania" ${currentCountry === 'Romania' ? 'selected' : ''}>Romania</option>
                <option value="Russian Federation" ${currentCountry === 'Russian Federation' ? 'selected' : ''}>Russian Federation</option>
                <option value="Rwanda" ${currentCountry === 'Rwanda' ? 'selected' : ''}>Rwanda</option>
                <option value="Saint Barthelemy" ${currentCountry === 'Saint Barthelemy' ? 'selected' : ''}>Saint Barthelemy</option>
                <option value="Saint Helena" ${currentCountry === 'Saint Helena' ? 'selected' : ''}>Saint Helena</option>
                <option value="Saint Kitts and Nevis" ${currentCountry === 'Saint Kitts and Nevis' ? 'selected' : ''}>Saint Kitts and Nevis</option>
                <option value="Saint Lucia" ${currentCountry === 'Saint Lucia' ? 'selected' : ''}>Saint Lucia</option>
                <option value="Saint Martin" ${currentCountry === 'Saint Martin' ? 'selected' : ''}>Saint Martin</option>
                <option value="Saint Pierre and Miquelon" ${currentCountry === 'Saint Pierre and Miquelon' ? 'selected' : ''}>Saint Pierre and Miquelon</option>
                <option value="Saint Vincent and the Grenadines" ${currentCountry === 'Saint Vincent and the Grenadines' ? 'selected' : ''}>Saint Vincent and the Grenadines</option>
                <option value="Samoa" ${currentCountry === 'Samoa' ? 'selected' : ''}>Samoa</option>
                <option value="San Marino" ${currentCountry === 'San Marino' ? 'selected' : ''}>San Marino</option>
                <option value="Sao Tome and Principe" ${currentCountry === 'Sao Tome and Principe' ? 'selected' : ''}>Sao Tome and Principe</option>
                <option value="Saudi Arabia" ${currentCountry === 'Saudi Arabia' ? 'selected' : ''}>Saudi Arabia</option>
                <option value="Senegal" ${currentCountry === 'Senegal' ? 'selected' : ''}>Senegal</option>
                <option value="Serbia" ${currentCountry === 'Serbia' ? 'selected' : ''}>Serbia</option>
                <option value="Serbia and Montenegro" ${currentCountry === 'Serbia and Montenegro' ? 'selected' : ''}>Serbia and Montenegro</option>
                <option value="Seychelles" ${currentCountry === 'Seychelles' ? 'selected' : ''}>Seychelles</option>
                <option value="Sierra Leone" ${currentCountry === 'Sierra Leone' ? 'selected' : ''}>Sierra Leone</option>
                <option value="Singapore" ${currentCountry === 'Singapore' ? 'selected' : ''}>Singapore</option>
                <option value="Sint Maarten" ${currentCountry === 'Sint Maarten' ? 'selected' : ''}>Sint Maarten</option>
                <option value="Slovakia" ${currentCountry === 'Slovakia' ? 'selected' : ''}>Slovakia</option>
                <option value="Slovenia" ${currentCountry === 'Slovenia' ? 'selected' : ''}>Slovenia</option>
                <option value="Solomon Islands" ${currentCountry === 'Solomon Islands' ? 'selected' : ''}>Solomon Islands</option>
                <option value="Somalia" ${currentCountry === 'Somalia' ? 'selected' : ''}>Somalia</option>
                <option value="South Africa" ${currentCountry === 'South Africa' ? 'selected' : ''}>South Africa</option>
                <option value="South Georgia and the South Sandwich Islands" ${currentCountry === 'South Georgia and the South Sandwich Islands' ? 'selected' : ''}>South Georgia and the South Sandwich Islands</option>
                <option value="South Sudan" ${currentCountry === 'South Sudan' ? 'selected' : ''}>South Sudan</option>
                <option value="Spain" ${currentCountry === 'Spain' ? 'selected' : ''}>Spain</option>
                <option value="Sri Lanka" ${currentCountry === 'Sri Lanka' ? 'selected' : ''}>Sri Lanka</option>
                <option value="Sudan" ${currentCountry === 'Sudan' ? 'selected' : ''}>Sudan</option>
                <option value="Suriname" ${currentCountry === 'Suriname' ? 'selected' : ''}>Suriname</option>
                <option value="Svalbard and Jan Mayen" ${currentCountry === 'Svalbard and Jan Mayen' ? 'selected' : ''}>Svalbard and Jan Mayen</option>
                <option value="Swaziland" ${currentCountry === 'Swaziland' ? 'selected' : ''}>Swaziland</option>
                <option value="Sweden" ${currentCountry === 'Sweden' ? 'selected' : ''}>Sweden</option>
                <option value="Switzerland" ${currentCountry === 'Switzerland' ? 'selected' : ''}>Switzerland</option>
                <option value="Syrian Arab Republic" ${currentCountry === 'Syrian Arab Republic' ? 'selected' : ''}>Syrian Arab Republic</option>
                <option value="Taiwan, Province of China" ${currentCountry === 'Taiwan, Province of China' ? 'selected' : ''}>Taiwan, Province of China</option>
                <option value="Tajikistan" ${currentCountry === 'Tajikistan' ? 'selected' : ''}>Tajikistan</option>
                <option value="Tanzania, United Republic of" ${currentCountry === 'Tanzania, United Republic of' ? 'selected' : ''}>Tanzania, United Republic of</option>
                <option value="Thailand" ${currentCountry === 'Thailand' ? 'selected' : ''}>Thailand</option>
                <option value="Timor-Leste" ${currentCountry === 'Timor-Leste' ? 'selected' : ''}>Timor-Leste</option>
                <option value="Togo" ${currentCountry === 'Togo' ? 'selected' : ''}>Togo</option>
                <option value="Tokelau" ${currentCountry === 'Tokelau' ? 'selected' : ''}>Tokelau</option>
                <option value="Tonga" ${currentCountry === 'Tonga' ? 'selected' : ''}>Tonga</option>
                <option value="Trinidad and Tobago" ${currentCountry === 'Trinidad and Tobago' ? 'selected' : ''}>Trinidad and Tobago</option>
                <option value="Tunisia" ${currentCountry === 'Tunisia' ? 'selected' : ''}>Tunisia</option>
                <option value="Turkey" ${currentCountry === 'Turkey' ? 'selected' : ''}>Turkey</option>
                <option value="Turkmenistan" ${currentCountry === 'Turkmenistan' ? 'selected' : ''}>Turkmenistan</option>
                <option value="Turks and Caicos Islands" ${currentCountry === 'Turks and Caicos Islands' ? 'selected' : ''}>Turks and Caicos Islands</option>
                <option value="Tuvalu" ${currentCountry === 'Tuvalu' ? 'selected' : ''}>Tuvalu</option>
                <option value="Uganda" ${currentCountry === 'Uganda' ? 'selected' : ''}>Uganda</option>
                <option value="Ukraine" ${currentCountry === 'Ukraine' ? 'selected' : ''}>Ukraine</option>
                <option value="United Arab Emirates" ${currentCountry === 'United Arab Emirates' ? 'selected' : ''}>United Arab Emirates</option>
                <option value="United Kingdom" ${currentCountry === 'United Kingdom' ? 'selected' : ''}>United Kingdom</option>
                <option value="United States" ${currentCountry === 'United States' ? 'selected' : ''}>United States</option>
                <option value="United States Minor Outlying Islands" ${currentCountry === 'United States Minor Outlying Islands' ? 'selected' : ''}>United States Minor Outlying Islands</option>
                <option value="Uruguay" ${currentCountry === 'Uruguay' ? 'selected' : ''}>Uruguay</option>
                <option value="Uzbekistan" ${currentCountry === 'Uzbekistan' ? 'selected' : ''}>Uzbekistan</option>
                <option value="Vanuatu" ${currentCountry === 'Vanuatu' ? 'selected' : ''}>Vanuatu</option>
                <option value="Venezuela" ${currentCountry === 'Venezuela' ? 'selected' : ''}>Venezuela</option>
                <option value="Vietnam" ${currentCountry === 'Vietnam' ? 'selected' : ''}>Vietnam</option>
                <option value="Virgin Islands, British" ${currentCountry === 'Virgin Islands, British' ? 'selected' : ''}>Virgin Islands, British</option>
                <option value="Virgin Islands, U.s." ${currentCountry === 'Virgin Islands, U.s.' ? 'selected' : ''}>Virgin Islands, U.s.</option>
                <option value="Wallis and Futuna" ${currentCountry === 'Wallis and Futuna' ? 'selected' : ''}>Wallis and Futuna</option>
                <option value="Western Sahara" ${currentCountry === 'Western Sahara' ? 'selected' : ''}>Western Sahara</option>
                <option value="Yemen" ${currentCountry === 'Yemen' ? 'selected' : ''}>Yemen</option>
                <option value="Zambia" ${currentCountry === 'Zambia' ? 'selected' : ''}>Zambia</option>
                <option value="Zimbabwe" ${currentCountry === 'Zimbabwe' ? 'selected' : ''}>Zimbabwe</option>
            `;

            $('#regCountryRow').html(`<td><b>Registration country:</b></td><td><select id="updatedRegCountry">${countryOptions}</select></td><td><i class="fa-solid fa-check" id="saveRegCountry"></i></td>`);
        });

        $('.editKyc').on('click', function() {
            $(this).hide();

            $('#kycRow').html(`<td><b>KYC Start:</b></td><td>
            Start: <input type="text" value="{{$company->kyc_start}}" id="updatedKycStart"><br>
            End: <input type="text" value="{{$company->kyc_end}}" id="updatedKycEnd"><br>
            Reason: <input type="text" value="{{$company->kyc_reason}}" id="updatedKycReason">
            </td><td><i class="fa-solid fa-check" id="saveKyc"></i></td>`);

            $( "#updatedKycStart" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
            $( "#updatedKycEnd" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "dd.mm.yy",
                constrainInput: false
            });
        });

        $('.panel-details').on('click', '#saveVat', function(){
            var updatedVat = $("#updatedVat").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {vat: updatedVat},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.panel-details').on('click', '#saveRegCountry', function(){
            var updatedRegCountry = $("#updatedRegCountry").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {registration_country: updatedRegCountry},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.panel-details').on('click', '#saveKyc', function(){
            var kycStart = $("#updatedKycStart").val();
            var kycEnd = $("#updatedKycEnd").val();
            var kycReason = $("#updatedKycReason").val();

            console.log(kycStart);
            console.log(kycEnd);
            console.log(kycReason);

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {kyc: true, kyc_start: kycStart, kyc_end: kycEnd, kyc_reason: kycReason},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editActivityCode').on('click', function() {
            $(this).hide();

            $('#activityCodeRow').html(`<td><b>Activity Code:</b></td><td><input type="text" value="{{ $company->activity_code }}" id="updatedActivityCode"></td><td><i class="fa-solid fa-check" id="saveActivityCode"></i></td>`);
        });

        $('.panel-details').on('click', '#saveActivityCode', function(){
            var updatedActivityCode = $("#updatedActivityCode").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {activity_code: updatedActivityCode},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editActivityCodeDescription').on('click', function() {
            $(this).hide();

            $('#activityCodeDescriptionRow').html(`<td><b>Activity Code Description:</b></td><td><textarea id="updatedActivityCodeDescription" rows="3" style="width: 100%;">{{ $company->activity_code_description }}</textarea></td><td><i class="fa-solid fa-check" id="saveActivityCodeDescription"></i></td>`);
        });

        $('.panel-details').on('click', '#saveActivityCodeDescription', function(){
            var updatedActivityCodeDescription = $("#updatedActivityCodeDescription").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
                data: {activity_code_description: updatedActivityCodeDescription},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.editAddress').on('click', function(){
            $(this).hide();

            $('#addressRow').html(`
                <td style="width:50%"><strong>Address:</strong></td>
                <td>
                                <input type="text" id="insertedAddressStreet" placeholder="Street Address" value="{{ $company->address_street }}"><br>
                                <input type="text" id="insertedAddressCity" placeholder="City" value="{{ $company->address_city }}"><br>
                                <input type="text" id="insertedAddressZip" placeholder="ZIP" value="{{ $company->address_zip }}"><br>
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
                            </td><td>
                    <i class="fa-solid fa-check" id="saveAddress"></i>
                </td>`);

        $("#insertedAddressDropdown").val("{{ $company->address_dropdown }}");

        });

        $('.panel-details').on('click', '#saveAddress', function(){
            var address_street = $("#insertedAddressStreet").val();
            var address_city = $("#insertedAddressCity").val();
            var address_zip = $("#insertedAddressZip").val();
            var address_dropdown = $("#insertedAddressDropdown").val();
            //var address_note = $("#insertedAddressNote").val();

            $.ajax({
                type: 'PUT',
                url: "{{ route('companies.update',$company->id) }}",
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

        $("#addNote .btn-submit").click(function () {

            var company_id = $("#companyID").val();
            var title = $("#noteTitle").val();
            var content = $("#noteContent").val();

            $.ajax({
                type: 'POST',
                url: "/notes/company/" + company_id,
                data: {company_id: company_id, title: title, content: content},
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload()
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });

        $('.note').on('click', '.fa-trash', function (e) {

            e.preventDefault();
            if (window.confirm("Remove Note?")) {

                var noteID = $(this).data().noteid;

                $.ajax({
                    type: 'POST',
                    url: "/notes/delete/" + noteID,
                    data: {company_id: {{ $company->id }} },
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
                data: {company_id: {{ $company->id }}, content: content },
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

        $('#deleteCompany').on('click', function(e){
            e.preventDefault();
            @if($company->persons->isEmpty() && $company->orders->isEmpty())
                if (window.confirm("Delete Company?")) {
                    var companyId = $(this).data('companyid');
                    $.ajax({
                        type: 'DELETE',
                        url: "/companies/"+companyId,
                        success: function (data) {
                            if ($.isEmptyObject(data.error)) {
                                window.location.replace("/companies");
                            } else {
                                printErrorMsg(data.error);
                            }
                        }
                    });
                }
            @else
                alert('Make sure the company has no related persons or orders!');
            @endif
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

                this.on("uploadprogress", function (file, progress) {
                    $(".progress-bar").css('width', progress + '%');
                    //console.log(file);
                    //console.log(progress);
                    //var progressBar = file.previewElement.querySelector(".progress-bar");
                    //console.log(progressBar);
                    //progressBar.style.width = progress + "%";
                    //progressBar.innerHTML = progress + "%";
                });

                this.on('sending', function(file, xhr, formData){
                    $('body').css('opacity', '0.5');
                    $('#loading').show();
                    formData.append('companyID', {{ $company->id }});
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
                    url:"/file/delete/company/{{ $company->id }}/"+fileName,
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
                                <input type="text" id="insertedAddressNote" placeholder="Notes"><br>
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
                url: "/entitycontact/new/{{$company->id}}",
                method: "POST",
                data: {value: newPhoneValue, type: 'phone', entity: 'company', note: newPhoneNote},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#emailRow').on('click', '#newEntityContactRow .fa-check', function(){
            var newEmailValue = $('#newEntityContactValue').val();
            var newEmailNote = $('#newEntityContactNote').val();

            $.ajax({
                url: "/entitycontact/new/{{$company->id}}",
                method: "POST",
                data: {value: newEmailValue, type: 'email', entity: 'company', note : newEmailNote},
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
            var address_note = $('#insertedAddressNote').val();


            $.ajax({
                url: "/entityaddress/new/{{$company->id}}",
                method: "POST",
                data: {company_id: {{$company->id}}, street: street, city: city, zip: zip, country: country, entity: 'company', address_note : address_note},
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
                <input type="text" id="updatedEmail" name="updatedEmail" value="`+contactValue+`" data-contactid="`+contactid+`">
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
                data: {value: updatedPhoneValue, company_id: {{$company->id}}, type: 'phone', entity: 'company', note: updatedPhoneNote},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#emailRow .fa-check').on('click', function(){
            var updatedEmailValue = $('#updatedEmail').val();
            var email_note = $('#updatedEmailNote').val();
            var contactid = $('#updatedEmail').data('contactid');

            $.ajax({
                url: "/entitycontact/update/"+contactid,
                method: "POST",
                data: {value: updatedEmailValue, company_id: {{$company->id}}, type: 'email', entity: 'company', note: email_note},
                success: function (data) {
                    window.location.reload();
                }
            });
        });

        $('#addressRow .fa-check').on('click', function(){
            var contactid = $(this).parent().siblings('.contactAddress').data('contactid');
            var street = $('#editAddressStreet').val();
            var city = $('#editAddressCity').val();
            var zip = $('#editAddressZip').val();
            var country = $('#editAddressDropdown').val();
            var address_note = $('#editAddressNote').val();

            $.ajax({
                    url: "/entityaddress/update/"+contactid,
                    method: "POST",
                    data: {street: street, city: city, zip: zip, country: country, entity: 'company', address_note : address_note, company_id: {{$company->id}}},
                success: function (data) {
                    window.location.reload();
                }
            });
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
                
                // Extract current values (you might need to adjust these selectors based on your HTML structure)
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

            } // end initCompanyShowMain

            // Start initialization
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCompanyShowMain);
            } else {
                initCompanyShowMain();
            }
        })();
    </script>

@endsection
