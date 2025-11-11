@extends('layouts.app')

@section('content')
<div data-page="order-show" data-entity-id="{{ $order->id }}">
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
    <a style="background:darkred!important;float:right;" target="_blank" class="btn btn-primary" data-orderid="{{$order->id}}" id="deleteOrder"><i class="fa-solid fa-trash"></i>Delete Order</a>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1><i class="fa-solid fa-file"></i>{{$order->number}} <span style="font-weight: 400;">{{ $order->name }}</span>
                @if($order->company)

                        <a href="/companies/{{ $order->company->id }}">{{ $order->company->name }}</a>

                @endif
                @if($order->person)

                        <a href="/persons/{{ $order->person->id }}">{{ $order->person->name }}</a>
                @endif
                <i class="fa-solid fa-pen-to-square"
                   style="cursor: pointer;vertical-align: middle; margin-left: 10px;font-size: 20px;"></i>
            </h1>
            @if(!$order->number)
                !!Order is missing Number!!
            @endif
            <br>
            Last updated: {{ $order->updated_at->format('d.m.Y H:i') }}
        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">
                        <button type="button" class="btn editDetails">
                            <i class="fa-solid fa-pen-to-square"></i>Edit
                        </button>
                        <button type="button" class="btn saveDetails" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>

                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addNewPayment">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add new payment
                        </button>

                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        @if($order->company)
                        <tr>
                            <td style="width:50%"><strong>Company:</strong></td>
                            <td id="currentCompany"><a href="/companies/{{ $order->company->id }}"><i class="fa-solid fa-building"></i>{{ $order->company->name }}</a></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                        @if($order->person)
                        <tr>
                            <td style="width:50%"><strong>Person:</strong></td>
                            <td id="currentPerson"><a href="/persons/{{ $order->person->id }}"><i class="fa-solid fa-user"></i>{{ $order->person->name }}</a></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                        <tr>
                            <td style="width:50%"><strong>Responsible User:</strong></td>
                            <td id="currentUser" data-currentResponsibleUserId="{{ $order->responsible_user->id }}"><i class="fa-solid fa-user-tie"></i>{{ $order->responsible_user->name }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Status:</strong></td>
                            <td id="currentStatus">{{ $order->status }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Payment status:</strong></td>
                            <td id="currentPaymentStatus">{{ $order->payment_status }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($order->awaiting_status)
                        <!--<tr>
                            <td style="width:50%"><strong>Awaiting status:</strong></td>
                            <td id="currentAwaitingStatus">{{ $order->awaiting_status }}</td>
                            <td></td>
                            <td></td>
                        </tr>-->
                        @endif
                        @if($order->paid_date && false)
                            <tr>
                                <td style="width:50%"><strong>Paid Date:</strong></td>
                                <td id="currentPaidDate">{{ $order->paid_date->format('d-m-Y') }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif

                        @if($order->payments)
                            @foreach($order->payments as $payment)
                                <tr class="paymentRow">
                                    <td style="width:50%"><strong>Payment:</strong></td>
                                    <td style="width:50%; word-break: break-word;">{{$payment->sum}}€<br>{{$payment->type}}<br>{{$payment->details}}<br>{{$payment->paid_date}}</td>
                                    <td style="padding:0;text-align: center;"><i class="fa-solid fa-pen-to-square" data-coreui-toggle="modal" data-coreui-target="#editPayment" data-paymentid="{{$payment->id}}" data-paymentSum="{{$payment->sum}}" data-paymentType="{{$payment->type}}" data-paymentDetails="{{$payment->details}}" data-paymentPaidDate="{{$payment->paid_date}}"></i><i style="display: none;" class="fa-solid fa-check"></i></td>
                                    <td style="padding:0;text-align: center;"><i style="color:darkred;" class="fa-solid fa-trash" data-paymentid="{{$payment->id}}"></i></td>
                                </tr>
                            @endforeach
                        @endif

                    </table>
                </div>
            </div>
        </div>
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
                    @if($order->notes)
                        <div>Vana note: {{$order->notes}}</div>
                    @endif
                    @foreach($order->getNotes as $note)
                        <div style="border-bottom: 1px solid black; padding: 5px 0;" class="note"><b>{{$note->responsible_user($note->user_id)->name}} ({{$note->created_at}})</b><i class="fa-solid fa-pen-to-square" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-check" style="display:none;vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><i class="fa-solid fa-trash" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-noteid="{{$note->id}}"></i><br>
                            <div class="noteContent" data-content="{{$note->content}}">{!! nl2br(e($note->content)) !!}</div></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-contactpersons">
                <div class="panel-heading">
                    <div class="panel-heading__title">Order contacts</div>
                    <div class="panel-heading__button">

                        <!--<button type="button" class="btn editNotes">
                            <i class="fa-solid fa-pen-to-square"></i>Edit
                        </button>-->

                        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                data-coreui-target="#addContactPerson">
                            <i class="fa fa-plus" aria-hidden="true"></i>Add contact person
                        </button>

                        <button type="button" class="btn saveContactPerson" style="display: none;">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <table width="100%">
                    @foreach($order->getOrderContacts as $person)
                        <tr>
                            @if($person->person_id)
                                <td class="person-name"><a href="/persons/{{$person->person_id}}">{{$person->name}}</a></td>
                            @else
                                <td class="person-name">{{$person->name}}</td>
                            @endif
                            <td class="person-email">{{$person->email}}</td>
                            <td><i class="fa-solid fa-pen-to-square" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-personid="{{$person->id}}"></i>
                                <i class="fa-solid fa-check" style="display:none;vertical-align: middle; margin-left: 10px;font-size: 20px;" data-personid="{{$person->id}}"></i>
                                <i class="fa-solid fa-trash" style="vertical-align: middle; margin-left: 10px;font-size: 20px;" data-personid="{{$person->id}}"></i></td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-services">
                <div class="panel-heading">
                    <div class="panel-heading__title">Services</div>
                    <div class="panel-heading__button">

                        @if(!$order->services->isEmpty())
                            <!--<button type="button" class="btn editServiceDetails">
                                <i class="fa-solid fa-pen-to-square"></i>Edit
                            </button>-->
                        @endif
                        @if($order->invoices->isEmpty())
                            <button type="button" class="btn btn-success" data-coreui-toggle="modal" data-coreui-target="#servicesModal">
                                <i class="fa fa-plus" aria-hidden="true"></i>Add Service
                            </button>
                        @endif
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        @if($order->company)
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $order->company->name }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                        @if($order->person)
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $order->person->name }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif

                        @php
                            $sum = 0;
                        @endphp
                        @foreach ($order->services as $service)
                            <tr class="addedService" data-orderServiceid="{{ $service->pivot->id }}">
                                <td id="addedServiceFrom-{{ $service->pivot->id }}">{{$service->pivot->date_from}}</td>
                                <td id="addedServiceTo-{{ $service->pivot->id }}">{{$service->pivot->date_to}}</td>
                                <td id="addedServiceName-{{ $service->pivot->id }}" data-serviceName="{{ $service->pivot->name }}" style="width:50%"><strong>{{ $service->pivot->name }}</strong></td>
                                <td id="addedServiceCost-{{ $service->pivot->id }}" data-serviceCost="{{ $service->pivot->cost }}">{{ $service->pivot->cost }}</td>
                                <td id="addedServiceType-{{ $service->pivot->id }}" data-serviceType="{{ $service->type }}">
                                    {{ $service->type }}
                                    <!--@if($service->type == 'Reaccuring')
                                        ({{ $service->reaccuring_frequency }}mo)
                                    @endif-->
                                </td>
                                <td>
                                    @if($order->invoices->isEmpty())
                                        <!--{{ $service->pivot }}-->
                                        <button type="button" class="btn editServiceDetails" data-orderServiceid="{{ $service->pivot->id }}">
                                            <i class="fa-solid fa-pen-to-square"></i>Edit
                                        </button>
                                        <button type="button" class="btn deleteService" data-orderServiceid="{{ $service->pivot->id }}">
                                            <i class="fa-solid fa-trash"></i>Delete
                                        </button>
                                    @endif
                                    <button type="button" class="btn saveServiceDetails" style="display: none;" data-pivotid="{{ $service->pivot->id }}">
                                        <i class="fa-solid fa-check"></i>Save
                                    </button></td>
                            </tr>
                            @php
                                $sum = $sum + $service->pivot->cost;
                            @endphp
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Net sum: </td>
                            <td><span id="servicesSum">{{ $sum }}</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>VAT @foreach ($order->invoices as $invoice) {{ $invoice->vat }}%: @endforeach </td>
                            <td><span id="servicesVAT">@foreach ($order->invoices as $invoice) {{ $invoice->vat * $sum / 100 }} @endforeach</span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total: </td>
                            <td><span id="servicesSum">@foreach ($order->invoices as $invoice) {{ $invoice->vat * $sum / 100 + $sum }} @endforeach</span></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-success" data-coreui-toggle="modal"
            data-coreui-target="#createInvoice" style="margin-top:10px;">
        <i class="fa fa-plus" aria-hidden="true"></i>Create Price offer
    </button>
    @if($order->invoices->isEmpty())
        <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                data-coreui-target="#invoiceModal" style="margin-top:10px;">
            <i class="fa fa-plus" aria-hidden="true"></i>Create Invoice
        </button>
    @else
        @foreach ($order->invoices as $invoice)
        <div class="row">
            <div class="col">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if($invoice->is_proforma)
                        <div class="panel-heading__title">Price offer ID: {{$invoice->id}}</div>
                        @elseif(!$invoice->is_proforma)
                            <div class="panel-heading__title">Invoice ID: {{$invoice->id}}</div>
                        @endif
                        <div class="panel-heading__button">
                            @if($invoice->is_proforma)
                                <a target="_blank" class="btn btn-primary deleteInvoice" data-invoiceid="{{$invoice->id}}"><i class="fa-solid fa-trash"></i>Delete Price offer</a>
                            @elseif(!$invoice->is_proforma)
                                <div id="invoice_exists"></div>
                                <a target="_blank" class="btn btn-primary deleteInvoice" data-invoiceid="{{$invoice->id}}"><i class="fa-solid fa-trash"></i>Delete Invoice</a>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>Issue Date</td>
                                    <td>{{ $invoice->issue_date }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Date</td>
                                    <td>{{ $invoice->payment_date }}</td>
                                </tr>
                                <tr>
                                    <td>VAT</td>
                                    <td><span id="invoiceVat">{{ $invoice->vat }}</span>%</td>
                                </tr>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-documents regularDocuments">
                <div class="panel-heading">
                    <div class="panel-heading__title">Documents <span id="documentsCount"></span></div>

                </div>
                <div class="panel-body">
                    <table class="table">
                        @foreach ($order->files as $file)
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
                                <td><a href="/file/order/{{ $order->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                <td><a href="/file/download/order/{{ $order->id }}/{{$file->name}}"target="_blank">DOWNLOAD</a></td>
                                <td>
                                    <a class="deleteDocument" href="/file/delete/order/{{ $order->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a>
                                </td>
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
                        @foreach ($order->files as $file)
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
                                <td><a href="/file/order/{{ $order->id }}/{{$file->name}}" target="_blank">VIEW</a></td>
                                <td><a href="/file/download/order/{{ $order->id }}/{{$file->name}}"target="_blank">DOWNLOAD</a></td>
                                <td><a class="deleteDocument" href="/file/delete/order/{{ $order->id }}/{{$file->name}}" data-filename="{{$file->name}}">DELETE</a></td>
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

    <!-- Proforma MODAL -->
    <div class="modal fade" id="createInvoice" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Price offer</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">

                        <form>
                            <p>Date: <input type="text" id="datepicker"></p>

                            <p>Payment date: <input type="text" id="paymentdatepicker"></p>

                            <p>VAT</p>

                            <input class="vatselection" type="radio" id="0" name="vatpercent" value="0">
                            <label for="o">0%</label><br>
                            <input class="vatselection" type="radio" id="22" name="vatpercent" value="22">
                            <label for="22">22%</label><br>
                            <input class="vatselection" type="radio" id="24" name="vatpercent" value="24">
                            <label for="24">24%</label><br><br>

                            <input type="hidden" name="orderID" value="{{ $order->id }}">
                            <input type="hidden" name="is_proforma" value="1">

                            <p>VAT Comment</p>
                            <textarea id="vat_comment" name="vat_comment" rows="4" cols="50"></textarea>

                        </form>

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
                        <input type="hidden" id="orderID" class="form-control" value="{{ $order->id }}">

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

    <div class="modal fade" id="addContactPerson" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Contact person</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="orderID" class="form-control" value="{{ $order->id }}">
                        <input type="hidden" id="person_id" class="form-control">
                        <label for="orderContactName">Name</label>
                        <input id="orderContactName" name="orderContactName">
                        <div id="searchResultsPerson" style=" display:none; position: absolute;background: white;padding: 10px;list-style: none;"></div>
                        <br>
                        <label for="orderContactEmail">E-mail</label>
                        <input id="orderContactEmail" name="orderContactEmail">
                        <div class="createPersonDiv">
                        <input type="checkbox" id="createPerson" name="createPerson" value="1">
                        <label for="createPerson">Create as new person</label><br>
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

    <div class="modal fade" id="addNewPayment" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Payment</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="orderID" class="form-control" value="{{ $order->id }}">
                        <label for="paidtype">Type</label>
                        <select name="paidtype" id="paidtype">
                            <option value="COOP">COOP</option>
                            <option value="Paysera">Paysera</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Revolut">Revolut</option>
                            <option value="Other">Other</option>
                        </select><br>
                        <label for="paidsum">Paid sum</label>
                        <input id="paidsum" name="paidsum"><br>
                        <label for="paiddate">Paid date</label>
                        <input id="paiddate" name="paiddate">
                        <div class="form-group">
                            <strong>Notes:</strong>
                            <textarea class="form-control" style="height:150px" id="paiddetails" name="paiddetails" placeholder="Details"></textarea>
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

    <div class="modal fade" id="editPayment" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Payment</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="orderID" class="form-control" value="{{ $order->id }}">
                        <input type="hidden" id="paymentID" class="form-control">
                        <label for="paidtype">Type</label>
                        <select name="paidtype" id="paidtype">
                            <option value="COOP">COOP</option>
                            <option value="Paysera">Paysera</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Revolut">Revolut</option>
                            <option value="Other">Other</option>
                        </select><br>
                        <label for="paidsum">Paid sum</label>
                        <input id="paidsum" name="paidsum"><br>
                        <label for="editpaiddate">Paid date</label>
                        <input id="editpaiddate" name="paiddate">
                        <div class="form-group">
                            <strong>Notes:</strong>
                            <textarea class="form-control" style="height:150px" id="paiddetails" name="paiddetails" placeholder="Details"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- INVOICE MODAL -->
    <div class="modal fade" id="invoiceModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Invoice</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <form>
                            <input type="radio" name="invoicecompany" value="wisor" checked>
                            <label for="wisor">Wisor Group OÜ</label><br>
                            <input type="radio" name="invoicecompany" value="corptailor">
                            <label for="corptailor">Corptailor OÜ</label><br>
                            @if($order->company)
                                <p>Payer Name: <input type="text" id="invoicePayerName" name="name"</p>
                            @elseif($order->person)
                                <p>Payer Name: <input type="text" id="invoicePayerName" name="name"></p>
                            @endif
                            <div id="invoicePayerNameResults" style="position: absolute;background: white;padding: 10px;list-style: none;"></div>
                            @if($order->company)
                                <p>Registry Code: <input type="text" id="reg_code" name="reg_code"></p>
                            @elseif($order->person)
                                <p>Registry Code: <input type="text" id="reg_code" name="reg_code"></p>
                            @endif

                            <p>Vat no: <input type="text" id="vat_no" name="vat_no"></p>

                            <p>VAT</p>

                            @forelse($order->invoices as $invoice)
                                @if($invoice->vat == 20)
                                    <input class="vatselection" type="radio" name="vatpercent" id="0" value="0">
                                    <label for="o">0%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="22" value="22" checked>
                                    <label for="22">22%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="24" value="24">
                                    <label for="24">24%</label><br><br>
                                @elseif($invoice->vat == 0)
                                    <input class="vatselection" type="radio" name="vatpercent" id="0" value="0" checked>
                                    <label for="o">0%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="22" value="22">
                                    <label for="22">22%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="24" value="24">
                                    <label for="24">24%</label><br><br>
                                @elseif($invoice->vat == 22)
                                    <input class="vatselection" type="radio" name="vatpercent" id="0" value="0">
                                    <label for="o">0%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="22" value="22" checked>
                                    <label for="22">22%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="24" value="24">
                                    <label for="24">24%</label><br><br>
                                @elseif($invoice->vat == 24)
                                    <input class="vatselection" type="radio" name="vatpercent" id="0" value="0">
                                    <label for="o">0%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="22" value="22">
                                    <label for="22">22%</label><br>
                                    <input class="vatselection" type="radio" name="vatpercent" id="24" value="24" checked>
                                    <label for="24">24%</label><br><br>
                                @endif

                                    <p>Invoice issue date: <input type="text" name="invoice_date" id="invoice_date_edit" value="{{ $invoice->issue_date }}"></p>
                                    <p>Invoice payment date: <input type="text" name="invoice_payment_date" id="invoice_payment_date_edit" value="{{ $invoice->payment_date }}"></p>
                            @empty

                                <input class="vatselection" type="radio" name="vatpercent" id="0" value="0">
                                <label for="o">0%</label><br>
                                <input class="vatselection" type="radio" name="vatpercent" id="22" value="22">
                                <label for="22">22%</label><br>
                                <input class="vatselection" type="radio" name="vatpercent" id="24" value="24">
                                <label for="24">24%</label><br><br>

                                <p>Invoice issue date: <input type="text" name="invoice_date" id="invoice_date_edit">(required)</p>
                                <p>Invoice payment date: <input type="text" name="invoice_payment_date" id="invoice_payment_date_edit">(required)</p>

                            @endforelse

                            <input type="hidden" name="orderID" value="{{ $order->id }}">
                            <input type="hidden" name="is_proforma" value="0">

                            <p>Street: <input type="text" id="invoiceStreet" name="invoiceStreet"></p>
                            <p>City: <input type="text" id="invoiceCity" name="invoiceCity"></p>
                            <p>Zip: <input type="text" id="invoiceZip" name="invoiceZip"></p>
                            <p>Country: <select id="invoiceCountry">
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
                                </select></p>
                            @if($order->company)
                                <!--<p>Address: <input type="text" id="address" name="invoiceAddress" value="{{ $order->company->address }}"></p>-->
                            @elseif($order->person)
                                <!--<p>Address: <input type="text" id="address" name="invoiceAddress" value="{{ $order->person->address }}"></p>-->
                            @endif
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- SERVICES MODAL -->
    <div class="modal fade" id="servicesModal" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" style="min-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Service</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="orderID" class="form-control" value="{{ $order->id }}">
                        <p>Services:</p>
                        <div class="accordion" id="dynamic-accordion">
                            @foreach($service_categories as $category)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{$category->id}}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$category->id}}" aria-expanded="false" aria-controls="collapse-{{$category->id}}">
                                            {{$category->name}}
                                        </button>
                                    </h2>
                                    <div id="collapse-{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="heading-{{$category->id}}">
                                        <div class="accordion-body">
                                            @foreach ($category->services as $service)
                                                <input class="serviceSelection" type="checkbox" id="{{ $service->id }}" name="service" value="{{ $service->id }}">
                                                <label for="{{ $service->id }}">{{ $service->name }}
                                                    <!--@if($service->type == 'Reaccuring')
                                                        Reaccuring - {{ $service->reaccuring_frequency }}mo
                                                    @endif-->
                                                </label> <span style="float:right;">{{ $service->cost }}eur</span><br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
</div>

<script>
    if (window.initOrderShow) {
        window.initOrderShow({
            entityId: {{ $order->id }},
            deleteRoute: '/file/delete/order',
            paymentStoreRoute: '/orders',
            paymentUpdateRoute: '/orders/payment/update',
            noteStoreRoute: '/notes/order',
            noteUpdateRoute: '/notes/update',
            noteDeleteRoute: '/notes/delete',
            contactStoreRoute: '/order/contact',
            contactUpdateRoute: '/order/contact/update',
            contactDeleteRoute: '/order/contact/delete',
            invoiceStoreRoute: '{{ route('invoices.store') }}',
            serviceStoreRoute: '/orders',
            serviceDeleteRoute: '/orders',
            uploadRoute: '/file/upload',
            updateRoute: '{{ route('orders.update', $order->id) }}'
        });
    }
</script>

@endsection
