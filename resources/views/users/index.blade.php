@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Users',
    'icon' => 'fa-user-tie',
    'count' => $users->total(),
    'countId' => 'usersCount',
    'singular' => 'user',
    'plural' => 'users',
    'createRoute' => 'users.create',
    'createButtonText' => 'New User'
])

@include('partials.success-alert')

@include('partials.search-bar', [
    'searchInputId' => 'userSearch',
    'placeholder' => 'Search by name or email...'
])

<!-- Users Table -->
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
                            <th style="width: 35%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </th>
                            <th style="width: 35%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                            </th>
                            <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> Created
                            </th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" style="background-color: #ffffff;">
                        @include('users.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.index-pagination', [
    'paginator' => $users,
    'paginationId' => 'usersPagination'
])

@endsection

@push('scripts')
@include('partials.index-styles', [
    'searchInputId' => 'userSearch',
    'paginationId' => 'usersPagination'
])

@include('partials.index-scripts', [
    'searchInputId' => 'userSearch',
    'tableBodyId' => 'usersTableBody',
    'paginationId' => 'usersPagination',
    'indexRoute' => 'users.index',
    'countId' => 'usersCount',
    'singular' => 'user',
    'plural' => 'users'
])
@endpush
