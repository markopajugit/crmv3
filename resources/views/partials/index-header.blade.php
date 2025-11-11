@php
    // Required parameters:
    // $title - The page title (e.g., "Companies", "Persons")
    // $icon - FontAwesome icon class (e.g., "fa-building", "fa-user")
    // $count - Total count from paginator
    // $countId - ID for the count element (e.g., "companiesCount", "personsCount")
    // $singular - Singular form of entity (e.g., "company", "person")
    // $plural - Plural form of entity (e.g., "companies", "persons")
    // $createRoute - Route name for create action (e.g., "companies.create", "persons.create")
    // $createButtonText - Text for create button (e.g., "New Company", "New Person")
@endphp

<!-- Header Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="mb-2 index-header-title">
                    <i class="fa-solid {{ $icon }} index-header-icon"></i> {{ $title }}
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.875rem; font-weight: 500;" id="{{ $countId }}">
                    <i class="fa-solid fa-database" style="margin-right: 0.25rem;"></i> Total: <strong style="color: #1f2937;">{{ $count }}</strong> {{ $count !== 1 ? $plural : $singular }}
                    <span id="searchIndicator" style="display: none; color: #6B7280;">| Showing search results</span>
                </p>
            </div>
            @if($createRoute && $createButtonText)
            <div class="dashboard-quick-actions">
                <a href="{{ route($createRoute) }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i> {{ $createButtonText }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

