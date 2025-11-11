@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Persons',
    'icon' => 'fa-user',
    'count' => $persons->total(),
    'countId' => 'personsCount',
    'singular' => 'person',
    'plural' => 'persons',
    'createRoute' => 'persons.create',
    'createButtonText' => 'New Person'
])

@include('partials.success-alert')

@include('partials.search-bar', [
    'searchInputId' => 'personSearch',
    'placeholder' => 'Search by name, ID code, email, phone, address...'
])

<!-- Persons Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-id-card" style="color: #DC2626; margin-right: 0.5rem;"></i> ID Code
                            </th>
                            <th style="width: 25%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-map-marker-alt" style="color: #DC2626; margin-right: 0.5rem;"></i> Address
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-phone" style="color: #DC2626; margin-right: 0.5rem;"></i> Phone
                            </th>
                        </tr>
                    </thead>
                    <tbody id="personsTableBody" style="background-color: #ffffff;">
                        @include('persons.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $persons,
    'paginationId' => 'personsPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'personSearch',
    'paginationId' => 'personsPagination'
])

@include('partials.index-scripts', [
    'searchInputId' => 'personSearch',
    'tableBodyId' => 'personsTableBody',
    'paginationId' => 'personsPagination',
    'indexRoute' => 'persons.index',
    'countId' => 'personsCount',
    'singular' => 'person',
    'plural' => 'persons'
])
@endpush
