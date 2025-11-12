@extends('layouts.app')

@section('content')

@include('partials.success-alert')

<!-- Two Column Layout -->
<div class="row">
    <!-- Services Section - Left Column -->
    <div class="col-md-6 mb-4">
        @include('partials.index-header', [
            'title' => 'Services',
            'icon' => 'fa-cog',
            'count' => $services->total(),
            'countId' => 'servicesCount',
            'singular' => 'service',
            'plural' => 'services',
            'createRoute' => 'services.create',
            'createButtonText' => 'New Service'
        ])

        @include('partials.search-bar', [
            'searchInputId' => 'serviceSearch',
            'placeholder' => 'Search by service name, cost, type, or category...'
        ])

        <!-- Services Table -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                            <thead style="background-color: #f9fafb;">
                                <tr>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-tag" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                                    </th>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-euro-sign" style="color: #DC2626; margin-right: 0.5rem;"></i> Cost
                                    </th>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-sync" style="color: #DC2626; margin-right: 0.5rem;"></i> Type
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="servicesTableBody" style="background-color: #ffffff;">
                                @include('services.partials.services-table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.index-pagination', [
            'paginator' => $services,
            'paginationId' => 'servicesPagination'
        ])
    </div>

    <!-- Service Categories Section - Right Column -->
    <div class="col-md-6 mb-4">
        @include('partials.index-header', [
            'title' => 'Service Categories',
            'icon' => 'fa-folder',
            'count' => $service_categories->total(),
            'countId' => 'categoriesCount',
            'singular' => 'category',
            'plural' => 'categories',
            'createRoute' => 'createCategory',
            'createButtonText' => 'New Category'
        ])

        @include('partials.search-bar', [
            'searchInputId' => 'categorySearch',
            'placeholder' => 'Search by category name...'
        ])

        <!-- Service Categories Table -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                            <thead style="background-color: #f9fafb;">
                                <tr>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-folder" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                                    </th>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-list" style="color: #DC2626; margin-right: 0.5rem;"></i> Count
                                    </th>
                                    <th style="padding: 0.75rem 0.875rem; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> Created
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTableBody" style="background-color: #ffffff;">
                                @include('services.partials.categories-table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.index-pagination', [
            'paginator' => $service_categories,
            'paginationId' => 'categoriesPagination'
        ])
    </div>
</div>

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'serviceSearch',
    'paginationId' => 'servicesPagination'
])

@include('partials.index-styles', [
    'searchInputId' => 'categorySearch',
    'paginationId' => 'categoriesPagination'
])

@include('partials.index-scripts-services', [
    'serviceSearchInputId' => 'serviceSearch',
    'serviceTableBodyId' => 'servicesTableBody',
    'servicePaginationId' => 'servicesPagination',
    'serviceCountId' => 'servicesCount',
    'categorySearchInputId' => 'categorySearch',
    'categoryTableBodyId' => 'categoriesTableBody',
    'categoryPaginationId' => 'categoriesPagination',
    'categoryCountId' => 'categoriesCount',
    'indexRoute' => 'services.index'
])
@endpush
