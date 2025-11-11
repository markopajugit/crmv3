@extends('layouts.app')

@section('content')

@include('partials.index-header', [
    'title' => 'Detailed Search',
    'icon' => 'fa-search',
    'count' => 0,
    'countId' => 'searchFormCount',
    'singular' => 'filter',
    'plural' => 'filters',
    'createRoute' => null,
    'createButtonText' => null
])

@include('partials.success-alert')

<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="card-header" style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb; padding: 1rem 1.25rem;">
                <h5 class="mb-0" style="font-weight: 600; color: #1f2937; font-size: 1rem;">
                    <i class="fa-solid fa-filter" style="color: #DC2626; margin-right: 0.5rem;"></i> Search Filters
                </h5>
            </div>
            <div class="card-body" style="padding: 1.25rem; background-color: #ffffff;">
                <form method="POST" action="{{ route('search.detailed') }}">
                    @csrf

                    <!-- Search Types -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem; display: block;">
                                <i class="fa-solid fa-list-check" style="color: #DC2626; margin-right: 0.5rem;"></i> Search In:
                            </label>
                            <div class="form-check form-check-inline" style="margin-right: 1.5rem;">
                                <input class="form-check-input" type="checkbox" name="search_types[]" value="persons" id="search_persons"
                                    {{ in_array('persons', old('search_types', [])) ? 'checked' : '' }}
                                    style="width: 1.125rem; height: 1.125rem; margin-right: 0.5rem; cursor: pointer;">
                                <label class="form-check-label" for="search_persons" style="font-size: 0.875rem; font-weight: 500; color: #374151; cursor: pointer;">
                                    <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.25rem;"></i> Persons
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" value="companies" id="search_companies"
                                    {{ in_array('companies', old('search_types', [])) ? 'checked' : '' }}
                                    style="width: 1.125rem; height: 1.125rem; margin-right: 0.5rem; cursor: pointer;">
                                <label class="form-check-label" for="search_companies" style="font-size: 0.875rem; font-weight: 500; color: #374151; cursor: pointer;">
                                    <i class="fa-solid fa-building" style="color: #DC2626; margin-right: 0.25rem;"></i> Companies
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Search Filters -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name') }}" placeholder="Enter name..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" placeholder="Enter email address..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone') }}" placeholder="Enter phone number..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="{{ old('address') }}" placeholder="Enter address (street, city, or zip)..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="registry_code" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Registry Code / ID Code</label>
                            <input type="text" class="form-control" id="registry_code" name="registry_code"
                                   value="{{ old('registry_code') }}" placeholder="Enter registry/ID code..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vat" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">VAT Number</label>
                            <input type="text" class="form-control" id="vat" name="vat"
                                   value="{{ old('vat') }}" placeholder="Enter VAT number..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="risk_level" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Risk Level</label>
                            <select class="form-select" id="risk_level" name="risk_level"
                                    style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                                <option value="">All Risk Levels</option>
                                <option value="low" {{ old('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('risk_level') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">General Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                   value="{{ old('country') }}" placeholder="Enter country..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <!-- New Country-specific search fields -->
                        <div class="col-md-6 mb-3">
                            <label for="birthplace_country" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Birthplace Country <small class="text-muted" style="font-weight: 400;">(Persons only)</small></label>
                            <input type="text" class="form-control" id="birthplace_country" name="birthplace_country"
                                   value="{{ old('birthplace_country') }}" placeholder="Enter birthplace country..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="citizenship_country" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Citizenship Country <small class="text-muted" style="font-weight: 400;">(Persons only)</small></label>
                            <input type="text" class="form-control" id="citizenship_country" name="citizenship_country"
                                   value="{{ old('citizenship_country') }}" placeholder="Enter citizenship country..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax_residency_country" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Tax Residency Country</label>
                            <input type="text" class="form-control" id="tax_residency_country" name="tax_residency_country"
                                   value="{{ old('tax_residency_country') }}" placeholder="Enter tax residency country..."
                                   style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                        </div>

                        <!-- PEP Status (Persons only) -->
                        <div class="col-md-6 mb-3">
                            <label for="pep_status" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">PEP Status <small class="text-muted" style="font-weight: 400;">(Persons only)</small></label>
                            <select class="form-select" id="pep_status" name="pep_status"
                                    style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                                <option value="">All PEP Statuses</option>
                                <option value="1" {{ old('pep_status') == '1' ? 'selected' : '' }}>Yes - PEP</option>
                                <option value="0" {{ old('pep_status') == '0' ? 'selected' : '' }}>No - Not PEP</option>
                            </select>
                        </div>

                        <!-- KYC Status -->
                        <div class="col-md-6 mb-3">
                            <label for="kyc_status" class="form-label" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">KYC Status</label>
                            <select class="form-select" id="kyc_status" name="kyc_status"
                                    style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 0.875rem;">
                                <option value="">All KYC Statuses</option>
                                <option value="active" {{ old('kyc_status') == 'active' ? 'selected' : '' }}>Active KYC</option>
                                <option value="expired" {{ old('kyc_status') == 'expired' ? 'selected' : '' }}>Expired KYC</option>
                                <option value="no_kyc" {{ old('kyc_status') == 'no_kyc' ? 'selected' : '' }}>No KYC Record</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" style="background-color: #DC2626; border: none; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 600; border-radius: 6px;">
                                <i class="fa-solid fa-search"></i> Search
                            </button>
                            <a href="{{ route('search.detailed.form') }}" class="btn btn-secondary" style="background-color: #6B7280; border: none; padding: 0.625rem 1.5rem; font-size: 0.875rem; font-weight: 600; border-radius: 6px; margin-left: 0.5rem;">
                                <i class="fa-solid fa-refresh"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div class="card-body" style="padding: 1.25rem; background-color: #EFF6FF; border-left: 4px solid #3B82F6;">
                <h6 style="font-weight: 600; color: #1E40AF; margin-bottom: 1rem;">
                    <i class="fa-solid fa-info-circle" style="color: #3B82F6; margin-right: 0.5rem;"></i> Search Tips
                </h6>
                <ul class="mb-0" style="color: #1E3A8A; font-size: 0.875rem; line-height: 1.75;">
                    <li style="margin-bottom: 0.5rem;">Select at least one search type (Persons or Companies)</li>
                    <li style="margin-bottom: 0.5rem;">You can use partial matches - for example, searching "john" will find "Johnson"</li>
                    <li style="margin-bottom: 0.5rem;">Leave fields empty to search across all values for that field</li>
                    <li style="margin-bottom: 0.5rem;">Address search includes street, city, and zip code</li>
                    <li style="margin-bottom: 0.5rem;"><strong>Country Search Options:</strong>
                        <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                            <li style="margin-bottom: 0.25rem;"><strong>General Country:</strong> Searches registration country and address country</li>
                            <li style="margin-bottom: 0.25rem;"><strong>Birthplace Country:</strong> Searches person's birthplace (persons only)</li>
                            <li style="margin-bottom: 0.25rem;"><strong>Citizenship Country:</strong> Searches person's citizenship (persons only)</li>
                            <li style="margin-bottom: 0.25rem;"><strong>Tax Residency Country:</strong> Searches active tax residency countries</li>
                        </ul>
                    </li>
                    <li style="margin-bottom: 0.5rem;">PEP (Politically Exposed Person) status applies only to persons</li>
                    <li>KYC Status: Active = valid KYC, Expired = past end date, No KYC = no records</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
