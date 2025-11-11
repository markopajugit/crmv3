@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Renewals',
    'icon' => 'fa-sync-alt',
    'count' => $renewedOrders->total(),
    'countId' => 'renewalsCount',
    'singular' => 'renewal',
    'plural' => 'renewals',
    'createRoute' => null,
    'createButtonText' => null
])

@include('partials.success-alert')

@include('partials.search-bar', [
    'searchInputId' => 'renewalSearch',
    'placeholder' => 'Search by order number, name, company, person...'
])

<!-- Renewals Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-hashtag" style="color: #DC2626; margin-right: 0.5rem;"></i> No
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-building" style="color: #DC2626; margin-right: 0.5rem;"></i> Company
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-file-invoice" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-info-circle" style="color: #DC2626; margin-right: 0.5rem;"></i> Status
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-credit-card" style="color: #DC2626; margin-right: 0.5rem;"></i> Payment Status
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-user-tie" style="color: #DC2626; margin-right: 0.5rem;"></i> Responsible
                            </th>
                        </tr>
                    </thead>
                    <tbody id="renewalsTableBody" style="background-color: #ffffff;">
                        @include('renewals.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $renewedOrders,
    'paginationId' => 'renewalsPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'renewalSearch',
    'paginationId' => 'renewalsPagination'
])

@include('partials.index-scripts', [
    'searchInputId' => 'renewalSearch',
    'tableBodyId' => 'renewalsTableBody',
    'paginationId' => 'renewalsPagination',
    'indexRoute' => 'renewals.index',
    'countId' => 'renewalsCount',
    'singular' => 'renewal',
    'plural' => 'renewals'
])
@endpush
