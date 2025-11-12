@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Invoices (' . ucfirst($type) . ')',
    'icon' => 'fa-file-invoice',
    'count' => $invoices->total(),
    'countId' => 'invoicesCount',
    'singular' => 'invoice',
    'plural' => 'invoices',
    'createRoute' => null,
    'createButtonText' => null
])

@include('partials.success-alert')

@include('partials.search-bar-invoices', [
    'searchInputId' => 'invoiceSearch',
    'placeholder' => 'Search by invoice number, payer name, order name...',
    'currentType' => $type
])

<!-- Invoices Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-hashtag" style="color: #DC2626; margin-right: 0.5rem;"></i> ID
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-hashtag" style="color: #DC2626; margin-right: 0.5rem;"></i> Number
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Recipient
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> Issue Date
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-calendar-check" style="color: #DC2626; margin-right: 0.5rem;"></i> Payment Date
                            </th>
                            <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-percent" style="color: #DC2626; margin-right: 0.5rem;"></i> VAT
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-file-invoice" style="color: #DC2626; margin-right: 0.5rem;"></i> Order
                            </th>
                        </tr>
                    </thead>
                    <tbody id="invoicesTableBody" style="background-color: #ffffff;">
                        @include('invoices.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $invoices,
    'paginationId' => 'invoicesPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'invoiceSearch',
    'paginationId' => 'invoicesPagination'
])

@include('partials.index-scripts-invoices', [
    'searchInputId' => 'invoiceSearch',
    'tableBodyId' => 'invoicesTableBody',
    'paginationId' => 'invoicesPagination',
    'indexRoute' => 'invoices.index',
    'countId' => 'invoicesCount',
    'singular' => 'invoice',
    'plural' => 'invoices'
])
@endpush
