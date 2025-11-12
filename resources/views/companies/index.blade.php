@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Companies',
    'icon' => 'fa-building',
    'count' => $companies->total(),
    'countId' => 'companiesCount',
    'singular' => 'company',
    'plural' => 'companies',
    'createRoute' => 'companies.create',
    'createButtonText' => 'New Company'
])

@include('partials.success-alert')

@include('partials.search-bar', [
    'searchInputId' => 'companySearch',
    'placeholder' => 'Search by name, registry code, country, VAT, email, address...'
])

<!-- Companies Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="width: 25%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-building" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-id-card" style="color: #DC2626; margin-right: 0.5rem;"></i> Reg Code
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-globe" style="color: #DC2626; margin-right: 0.5rem;"></i> Reg Country
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-receipt" style="color: #DC2626; margin-right: 0.5rem;"></i> VAT
                            </th>
                            <th style="width: 25%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                            </th>
                        </tr>
                    </thead>
                    <tbody id="companiesTableBody" style="background-color: #ffffff;">
                        @include('companies.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $companies,
    'paginationId' => 'companiesPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'companySearch',
    'paginationId' => 'companiesPagination'
])

@include('partials.index-scripts', [
    'searchInputId' => 'companySearch',
    'tableBodyId' => 'companiesTableBody',
    'paginationId' => 'companiesPagination',
    'indexRoute' => 'companies.index',
    'countId' => 'companiesCount',
    'singular' => 'company',
    'plural' => 'companies'
])
@endpush
