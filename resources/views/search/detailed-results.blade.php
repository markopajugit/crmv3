@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-9">
                <h1>Detailed Search Results</h1>
                <p class="text-muted">
                    Showing results for:
                    @if(in_array('persons', $searchTypes) && in_array('companies', $searchTypes))
                        Persons & Companies
                    @elseif(in_array('persons', $searchTypes))
                        Persons
                    @elseif(in_array('companies', $searchTypes))
                        Companies
                    @else
                        No search type selected
                    @endif
                </p>
            </div>
            <div class="col-3 text-end">
                <a href="{{ route('search.detailed.form') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-search"></i> New Search
                </a>
            </div>
        </div>

        <!-- Active Filters -->
        @php
            $activeFilters = array_filter($filters);
        @endphp
        @if(count($activeFilters) > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-light">
                        <strong>Active Filters:</strong>
                        @foreach($activeFilters as $key => $value)
                            <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Companies Results -->
        @if(in_array('companies', $searchTypes) && count($companies) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-building"></i> Companies ({{ count($companies) }} found)
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Registry Code</th>
                                        <th>Country</th>
                                        <th>VAT</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Risk Level</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>
                                                <strong>{{ $company->name }}</strong>

                                                @if($company->address_street || $company->address_city)
                                                    <br><small class="text-muted">
                                                        {{ $company->address_street }}
                                                        @if($company->address_street && $company->address_city), @endif
                                                        {{ $company->address_city }}
                                                        @if($company->address_zip) {{ $company->address_zip }} @endif
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $company->registry_code ?: 'N/A' }}</td>
                                            <td>
                                                {{ $company->registration_country ?: 'N/A' }}
                                                @if($company->registration_country_abbr && $company->registration_country_abbr !== 'N/A')
                                                    <br><small class="text-muted">({{ $company->registration_country_abbr }})</small>
                                                @endif
                                            </td>
                                            <td>{{ $company->vat ?: 'N/A' }}</td>
                                            <td>
                                                @if($company->email)
                                                    <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->phone)
                                                    <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($company->getCurrentRisk)
                                                    @php
                                                        $risk = $company->getCurrentRisk->risk_level ?? 'Unknown';
                                                        $badgeClass = match(strtolower($risk)) {
                                                            'low' => 'bg-success',
                                                            'medium' => 'bg-warning',
                                                            'high' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($risk) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </a>
                                                <!--<a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa-solid fa-edit"></i>
                                                    Edit
                                                </a>-->
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(in_array('companies', $searchTypes))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-building"></i> No companies found matching your search criteria.
                    </div>
                </div>
            </div>
        @endif

        <!-- Persons Results -->
        @if(in_array('persons', $searchTypes) && count($persons) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-user"></i> Persons ({{ count($persons) }} found)
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>ID Code</th>
                                        <th>Country</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Date of Birth</th>
                                        <th>PEP Status</th>
                                        <th>Risk Level</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($persons as $person)
                                        <tr>
                                            <td>
                                                <strong>{{ $person->name }}</strong>
                                                @if($person->address_street || $person->address_city)
                                                    <br><small class="text-muted">
                                                        {{ $person->address_street }}
                                                        @if($person->address_street && $person->address_city), @endif
                                                        {{ $person->address_city }}
                                                        @if($person->address_zip) {{ $person->address_zip }} @endif
                                                    </small>
                                                @endif
                                            </td>
                                            <td>{{ $person->id_code ?: 'N/A' }}</td>
                                            <td>{{ $person->country ?: 'N/A' }}</td>
                                            <td>
                                                @if($person->email)
                                                    <a href="mailto:{{ $person->email }}">{{ $person->email }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($person->phone)
                                                    <a href="tel:{{ $person->phone }}">{{ $person->phone }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            <td>{{ $person->date_of_birth ?: 'N/A' }}</td>
                                            <td>
                                                @if($person->pep === 1)
                                                    <span class="badge bg-warning">PEP</span>
                                                @elseif($person->pep === 0)
                                                    <span class="badge bg-success">Not PEP</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($person->getCurrentRisk)
                                                    @php
                                                        $risk = $person->getCurrentRisk->risk_level ?? 'Unknown';
                                                        $badgeClass = match(strtolower($risk)) {
                                                            'low' => 'bg-success',
                                                            'medium' => 'bg-warning',
                                                            'high' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($risk) }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('persons.show', $person->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(in_array('persons', $searchTypes))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-user"></i> No persons found matching your search criteria.
                    </div>
                </div>
            </div>
        @endif

        <!-- No Search Type Selected -->
        @if(!in_array('persons', $searchTypes) && !in_array('companies', $searchTypes))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-exclamation-triangle"></i> Please select at least one search type (Persons or Companies) to perform a search.
                        <br>
                        <a href="{{ route('search.detailed.form') }}" class="btn btn-primary mt-2">
                            <i class="fa-solid fa-arrow-left"></i> Back to Search Form
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary -->
        @if((in_array('persons', $searchTypes) || in_array('companies', $searchTypes)) && (count($companies) > 0 || count($persons) > 0))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <strong>Search Summary:</strong>
                        Found {{ count($companies) + count($persons) }} total results
                        @if(count($companies) > 0 && count($persons) > 0)
                            ({{ count($companies) }} companies, {{ count($persons) }} persons)
                        @elseif(count($companies) > 0)
                            ({{ count($companies) }} companies)
                        @elseif(count($persons) > 0)
                            ({{ count($persons) }} persons)
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
