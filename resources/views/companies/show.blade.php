@extends('layouts.app')

@section('content')
<div data-page="company-show" data-entity-id="{{ $company->id }}">
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
</div>

<script>
    if (window.initCompanyShow) {
        window.initCompanyShow({
            entityId: {{ $company->id }},
            kycStoreRoute: '{{ route('kyc.store') }}',
            kycUpdateRoute: '/kyc/update',
            userSearchRoute: '{{ route('autoCompleteModalUser') }}',
            riskUpdateRoute: '/company/risk/update',
            taxResidencyUpdateRoute: '/entitycontact/update/0',
            updateRoute: '{{ route('companies.update', $company->id) }}'
        });
    }
</script>

@endsection
