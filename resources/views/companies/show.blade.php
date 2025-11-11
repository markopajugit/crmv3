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
            <h1 data-company-number="{{ $company->number }}" data-company-name="{{ $company->name }}"><i class="fa-solid fa-building"></i><i>{{ $company->number }}</i> {{ $company->name }}<i class="fa-solid fa-pen-to-square"
                                                                           style="cursor: pointer;vertical-align: middle; margin-left: 10px;font-size: 20px;"></i>
                @if($company->deleted)
                    (Deleted)
                @endif
                @if($company->registry_code)
                    <a target="_blank" href="https://ariregister.rik.ee/est/company/{{$company->registry_code}}"><i class="fa-solid fa-share-from-square fa-2xs"></i></a>
                @endif
            </h1>

            <h5 data-registry-code="{{ $company->registry_code }}">Reg: {{ $company->registry_code }}
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
                        <div class="mb-3">
                            <label for="persons" class="form-label">Persons</label>
                            <input type="hidden" name="persons" id="personID">
                            <div class="position-relative">
                                <input id="searchPerson" class="form-control" type="search" autocomplete="off"
                                       placeholder="Search for person" name="s" aria-label="Search">
                                <div id="searchResultsPerson" class="search-results" style="display:none;"></div>
                            </div>
                        </div>

                        <div id="emails" class="mb-3"></div>
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

                        <div class="mb-3">
                            <label for="searchUser" class="form-label">Responsible User</label>
                            <input type="hidden" name="users" id="userID">
                            <div class="position-relative">
                                <input id="searchUser" class="form-control" type="search" autocomplete="off"
                                       placeholder="Search for user" name="s" aria-label="Search">
                                <div id="searchResultsUser" class="search-results" style="display:none;"></div>
                            </div>
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

                        <div class="mb-3">
                            <label for="noteContent" class="form-label">Content</label>
                            <textarea id="noteContent" name="noteContent" class="form-control" rows="4" placeholder="Enter note content"></textarea>
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

                        <div class="mb-3">
                            <label for="searchPerson2" class="form-label">Person or Company</label>
                            <input type="hidden" name="persons" id="selectedPersonRelation">
                            <input type="hidden" name="companies" id="selectedCompanyRelation">
                            <div class="position-relative">
                                <input id="searchPerson2" class="form-control" type="search" autocomplete="off"
                                       placeholder="Search for person or company" name="s" aria-label="Search">
                                <div id="searchResultsPerson2" class="search-results" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Relation</label>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="boardmember" name="boardmember" value="Board Member">
                                <label class="form-check-label" for="boardmember">Board Member</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="shareholder" name="shareholder" value="Shareholder">
                                <label class="form-check-label" for="shareholder">Shareholder</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="agent" name="agent" value="Agent">
                                <label class="form-check-label" for="agent">Agent</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="agentrepresentative" name="agentrepresentative" value="Agent representative">
                                <label class="form-check-label" for="agentrepresentative">Agent representative</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="ubo" name="ubo" value="UBO">
                                <label class="form-check-label" for="ubo">UBO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="procura" name="procura" value="Procura">
                                <label class="form-check-label" for="procura">Procura</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="acp" name="acp" value="Authorised contact person">
                                <label class="form-check-label" for="acp">Authorised contact person</label>
                                <input id="authorised_person_deadline" type="text" class="form-control form-control-sm d-inline-block ms-2" style="width: auto;" placeholder="Deadline">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="auditor" name="auditor" value="Auditor">
                                <label class="form-check-label" for="auditor">Auditor</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input personCompanyRelation" type="checkbox" id="client" name="client" value="Client">
                                <label class="form-check-label" for="client">Client</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="otherRelation" class="form-label">Other</label>
                            <input class="form-control personCompanyRelationOther" type="text" id="otherRelation" name="other" placeholder="Enter other relation type">
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
                            <div class="position-relative">
                                <input id="kycResponsibleUser" class="form-control" type="search" autocomplete="off" placeholder="Search for responsible user" aria-label="Search">
                                <div id="kycResponsibleUserResults" class="search-results" style="display:none;"></div>
                            </div>
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
                            <div class="position-relative">
                                <input id="editKycResponsibleUser" class="form-control" type="search" autocomplete="off" placeholder="Search for responsible user" aria-label="Search">
                                <div id="editKycResponsibleUserResults" class="search-results" style="display:none;"></div>
                            </div>
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

@push('scripts')
    <script type="text/javascript">
        // Helper function to generate country options HTML
        // Uses the same COUNTRIES array from CompanyEditor component
        function generateCountryOptions(selectedValue = '') {
            const countries = ['Afghanistan', 'Aland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla',
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
                'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'];
            
            let html = '<option value="">country</option>';
            countries.forEach(country => {
                const selected = country === selectedValue ? ' selected' : '';
                html += `<option value="${country}"${selected}>${country}</option>`;
            });
            return html;
        }

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

        // Tax residency editing is now handled by CompanyEditor component
        // Old inline code removed - see resources/js/components/company-editor.js

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

        // All field editing (notes, date, VAT, registration country, KYC, activity codes) 
        // is now handled by CompanyEditor component - see resources/js/components/company-editor.js

        // Address editing is now handled by CompanyEditor component
        // Old inline code removed - see resources/js/components/company-editor.js

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

            } // end initCompanyShowMain

            // Start initialization
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCompanyShowMain);
            } else {
                initCompanyShowMain();
            }
        })();

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

        // Ensure jQuery is loaded before executing jQuery-dependent code
        (function() {
            function initCompanyShowSecondary() {
                if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
                    setTimeout(initCompanyShowSecondary, 50);
                    return;
                }

                var $ = window.jQuery;

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
                                <select id="insertedAddressDropdown">${generateCountryOptions()}</select>
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
                                <select id="editAddressDropdown">${generateCountryOptions(country)}</select>
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

            } // end initCompanyShowSecondary

            // Start initialization
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCompanyShowSecondary);
            } else {
                initCompanyShowSecondary();
            }
        })();


    </script>

    <script>
        // KYC Functionality
        // Ensure jQuery is loaded before executing jQuery-dependent code
        (function() {
            function initCompanyShowKYC() {
                if (typeof window.$ === 'undefined' || typeof window.jQuery === 'undefined') {
                    setTimeout(initCompanyShowKYC, 50);
                    return;
                }

                var $ = window.jQuery;

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
        }); // end $(document).ready

        // Initialize Company Editor Component
        if (typeof CompanyEditor !== 'undefined') {
            const companyEditor = new CompanyEditor(
                {{ $company->id ?? 0 }},
                "{{ route('companies.update', $company->id ?? 0) }}"
            );
        }

            } // end initCompanyShowKYC

            // Start initialization
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCompanyShowKYC);
            } else {
                initCompanyShowKYC();
            }
        })();
    </script>
@endpush

@endsection
