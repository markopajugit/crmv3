@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Detailed Search</h1>
                <p class="text-muted">Search through Persons and Companies with advanced filters</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Search Filters</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('search.detailed') }}">
                    @csrf

                    <!-- Search Types -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Search In:</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" value="persons" id="search_persons"
                                    {{ in_array('persons', old('search_types', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_persons">
                                    <i class="fa-solid fa-user"></i> Persons
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="search_types[]" value="companies" id="search_companies"
                                    {{ in_array('companies', old('search_types', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="search_companies">
                                    <i class="fa-solid fa-building"></i> Companies
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Search Filters -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name') }}" placeholder="Enter name...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ old('email') }}" placeholder="Enter email address...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone') }}" placeholder="Enter phone number...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="{{ old('address') }}" placeholder="Enter address (street, city, or zip)...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="registry_code" class="form-label">Registry Code / ID Code</label>
                            <input type="text" class="form-control" id="registry_code" name="registry_code"
                                   value="{{ old('registry_code') }}" placeholder="Enter registry/ID code...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vat" class="form-label">VAT Number</label>
                            <input type="text" class="form-control" id="vat" name="vat"
                                   value="{{ old('vat') }}" placeholder="Enter VAT number...">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="risk_level" class="form-label">Risk Level</label>
                            <select class="form-select" id="risk_level" name="risk_level">
                                <option value="">All Risk Levels</option>
                                <option value="1" {{ old('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="2" {{ old('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="3" {{ old('risk_level') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">General Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                   value="{{ old('country') }}" placeholder="Enter country...">
                        </div>

                        <!-- New Country-specific search fields -->
                        <div class="col-md-6 mb-3">
                            <label for="birthplace_country" class="form-label">Birthplace Country <small class="text-muted">(Persons only)</small></label>
                            <input type="text" class="form-control" id="birthplace_country" name="birthplace_country"
                                   value="{{ old('birthplace_country') }}" placeholder="Enter birthplace country...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="citizenship_country" class="form-label">Citizenship Country <small class="text-muted">(Persons only)</small></label>
                            <input type="text" class="form-control" id="citizenship_country" name="citizenship_country"
                                   value="{{ old('citizenship_country') }}" placeholder="Enter citizenship country...">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax_residency_country" class="form-label">Tax Residency Country</label>
                            <input type="text" class="form-control" id="tax_residency_country" name="tax_residency_country"
                                   value="{{ old('tax_residency_country') }}" placeholder="Enter tax residency country...">
                        </div>

                        <!-- PEP Status (Persons only) -->
                        <div class="col-md-6 mb-3">
                            <label for="pep_status" class="form-label">PEP Status <small class="text-muted">(Persons only)</small></label>
                            <select class="form-select" id="pep_status" name="pep_status">
                                <option value="">All PEP Statuses</option>
                                <option value="1" {{ old('pep_status') == '1' ? 'selected' : '' }}>Yes - PEP</option>
                                <option value="0" {{ old('pep_status') == '0' ? 'selected' : '' }}>No - Not PEP</option>
                            </select>
                        </div>

                        <!-- KYC Status -->
                        <div class="col-md-6 mb-3">
                            <label for="kyc_status" class="form-label">KYC Status</label>
                            <select class="form-select" id="kyc_status" name="kyc_status">
                                <option value="">All KYC Statuses</option>
                                <option value="active" {{ old('kyc_status') == 'active' ? 'selected' : '' }}>Active KYC</option>
                                <option value="expired" {{ old('kyc_status') == 'expired' ? 'selected' : '' }}>Expired KYC</option>
                                <option value="no_kyc" {{ old('kyc_status') == 'no_kyc' ? 'selected' : '' }}>No KYC Record</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-search"></i> Search
                            </button>
                            <a href="{{ route('search.detailed.form') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-refresh"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="fa-solid fa-info-circle"></i> Search Tips:</h6>
                    <ul class="mb-0">
                        <li>Select at least one search type (Persons or Companies)</li>
                        <li>You can use partial matches - for example, searching "john" will find "Johnson"</li>
                        <li>Leave fields empty to search across all values for that field</li>
                        <li>Address search includes street, city, and zip code</li>
                        <li><strong>Country Search Options:</strong>
                            <ul>
                                <li><strong>General Country:</strong> Searches registration country and address country</li>
                                <li><strong>Birthplace Country:</strong> Searches person's birthplace (persons only)</li>
                                <li><strong>Citizenship Country:</strong> Searches person's citizenship (persons only)</li>
                                <li><strong>Tax Residency Country:</strong> Searches active tax residency countries</li>
                            </ul>
                        </li>
                        <li>PEP (Politically Exposed Person) status applies only to persons</li>
                        <li>KYC Status: Active = valid KYC, Expired = past end date, No KYC = no records</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
