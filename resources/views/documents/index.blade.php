@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Documents',
    'icon' => 'fa-file-alt',
    'count' => $files->total(),
    'countId' => 'documentsCount',
    'singular' => 'document',
    'plural' => 'documents',
    'createRoute' => null,
    'createButtonText' => null
])

@include('partials.success-alert')

@include('partials.search-bar-documents', [
    'searchInputId' => 'documentSearch',
    'placeholder' => 'Search by file name, archive number, company, person, order...',
    'currentCategory' => $category
])

<!-- Documents Table -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                    <thead style="background-color: #f9fafb;">
                        <tr>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-folder" style="color: #DC2626; margin-right: 0.5rem;"></i> Category
                            </th>
                            <th style="width: 30%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-file" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-archive" style="color: #DC2626; margin-right: 0.5rem;"></i> Archive Nr
                            </th>
                            <th style="width: 25%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-link" style="color: #DC2626; margin-right: 0.5rem;"></i> Related To
                            </th>
                            <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> Created
                            </th>
                        </tr>
                    </thead>
                    <tbody id="documentsTableBody" style="background-color: #ffffff;">
                        @include('documents.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $files,
    'paginationId' => 'documentsPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'documentSearch',
    'paginationId' => 'documentsPagination'
])

@include('partials.index-scripts-documents', [
    'searchInputId' => 'documentSearch',
    'tableBodyId' => 'documentsTableBody',
    'paginationId' => 'documentsPagination',
    'indexRoute' => 'documents.index',
    'countId' => 'documentsCount',
    'singular' => 'document',
    'plural' => 'documents'
])
@endpush

