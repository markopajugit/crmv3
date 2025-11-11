@extends('layouts.app')

@section('content')

@php
    $totalResults = count($companies) + count($persons);
    $searchTypeText = '';
    if(in_array('persons', $searchTypes) && in_array('companies', $searchTypes)) {
        $searchTypeText = 'Persons & Companies';
    } elseif(in_array('persons', $searchTypes)) {
        $searchTypeText = 'Persons';
    } elseif(in_array('companies', $searchTypes)) {
        $searchTypeText = 'Companies';
    } else {
        $searchTypeText = 'Search Results';
    }
@endphp

@include('partials.index-header', [
    'title' => 'Detailed Search Results',
    'icon' => 'fa-search',
    'count' => $totalResults,
    'countId' => 'searchResultsCount',
    'singular' => 'result',
    'plural' => 'results',
    'createRoute' => 'search.detailed.form',
    'createButtonText' => 'New Search'
])

@include('partials.success-alert')

<!-- Active Filters -->
@php
    $activeFilters = array_filter($filters);
@endphp
@if(count($activeFilters) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <div class="card-header" style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; padding: 1rem 1.25rem;">
                    <h5 class="mb-0" style="font-weight: 600; color: #1f2937; font-size: 1rem;">
                        <i class="fa-solid fa-filter" style="color: #DC2626; margin-right: 0.5rem;"></i> Active Filters
                        <span style="color: #6B7280; font-weight: 500; font-size: 0.875rem;">({{ count($activeFilters) }})</span>
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.25rem; background-color: #ffffff;">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                        @foreach($activeFilters as $key => $value)
                            <span class="badge" style="padding: 0.5rem 0.875rem; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; background-color: #DC2626; color: #ffffff; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-tag" style="font-size: 0.75rem;"></i>
                                <span style="font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                <span>{{ $value }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Companies Results -->
@if(in_array('companies', $searchTypes))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <div class="card-header" style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; padding: 1rem 1.25rem;">
                    <h5 class="mb-0" style="font-weight: 600; color: #1f2937; font-size: 1rem;">
                        <i class="fa-solid fa-building" style="color: #DC2626; margin-right: 0.5rem;"></i> Companies
                        @if(count($companies) > 0)
                            <span style="color: #6B7280; font-weight: 500;">({{ count($companies) }} found)</span>
                        @endif
                    </h5>
                </div>
                @if(count($companies) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                            <thead style="background-color: #f9fafb;">
                                <tr>
                                    <th style="width: 25%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-building" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                                    </th>
                                    <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-id-card" style="color: #DC2626; margin-right: 0.5rem;"></i> Reg Code
                                    </th>
                                    <th style="width: 12%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-globe" style="color: #DC2626; margin-right: 0.5rem;"></i> Country
                                    </th>
                                    <th style="width: 12%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-receipt" style="color: #DC2626; margin-right: 0.5rem;"></i> VAT
                                    </th>
                                    <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                                    </th>
                                    <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-phone" style="color: #DC2626; margin-right: 0.5rem;"></i> Phone
                                    </th>
                                    <th style="width: 11%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-shield-halved" style="color: #DC2626; margin-right: 0.5rem;"></i> Risk
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffffff;">
                                @include('search.partials.companies-table')
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body" style="padding: 3rem 1.25rem; text-align: center;">
                        <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
                        <span style="font-size: 0.875rem; color: #6B7280;">No companies found matching your search criteria.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Persons Results -->
@if(in_array('persons', $searchTypes))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <div class="card-header" style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; padding: 1rem 1.25rem;">
                    <h5 class="mb-0" style="font-weight: 600; color: #1f2937; font-size: 1rem;">
                        <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Persons
                        @if(count($persons) > 0)
                            <span style="color: #6B7280; font-weight: 500;">({{ count($persons) }} found)</span>
                        @endif
                    </h5>
                </div>
                @if(count($persons) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="width: 100%; margin-bottom: 0;">
                            <thead style="background-color: #f9fafb;">
                                <tr>
                                    <th style="width: 20%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                                    </th>
                                    <th style="width: 12%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-id-card" style="color: #DC2626; margin-right: 0.5rem;"></i> ID Code
                                    </th>
                                    <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-globe" style="color: #DC2626; margin-right: 0.5rem;"></i> Country
                                    </th>
                                    <th style="width: 15%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                                    </th>
                                    <th style="width: 12%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-phone" style="color: #DC2626; margin-right: 0.5rem;"></i> Phone
                                    </th>
                                    <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> DOB
                                    </th>
                                    <th style="width: 10%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-user-shield" style="color: #DC2626; margin-right: 0.5rem;"></i> PEP
                                    </th>
                                    <th style="width: 11%; padding: 1rem 1.25rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; border-bottom: 2px solid #e5e7eb;">
                                        <i class="fa-solid fa-shield-halved" style="color: #DC2626; margin-right: 0.5rem;"></i> Risk
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="background-color: #ffffff;">
                                @include('search.partials.persons-table')
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body" style="padding: 3rem 1.25rem; text-align: center;">
                        <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #D1D5DB;"></i>
                        <span style="font-size: 0.875rem; color: #6B7280;">No persons found matching your search criteria.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- No Search Type Selected -->
@if(!in_array('persons', $searchTypes) && !in_array('companies', $searchTypes))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <div class="card-body" style="padding: 2rem 1.25rem; text-align: center; background-color: #FEF3C7; border-left: 4px solid #F59E0B;">
                    <i class="fa-solid fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #F59E0B;"></i>
                    <p style="color: #92400E; font-weight: 500; margin-bottom: 1rem;">Please select at least one search type (Persons or Companies) to perform a search.</p>
                    <a href="{{ route('search.detailed.form') }}" class="btn btn-primary" style="background-color: #DC2626; border: none;">
                        <i class="fa-solid fa-arrow-left"></i> Back to Search Form
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
