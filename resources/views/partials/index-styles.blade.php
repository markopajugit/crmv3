@php
    // Required parameters:
    // $searchInputId - ID for search input (e.g., "companySearch", "personSearch")
    // $paginationId - ID for pagination container (e.g., "companiesPagination", "personsPagination")
@endphp

<style>
    #{{ $searchInputId }}:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    
    .input-group:focus-within {
        border-color: #DC2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
    
    #clearSearch:hover {
        background-color: #4B5563 !important;
    }
    
    /* Pagination Styling */
    #{{ $paginationId }} .pagination {
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    #{{ $paginationId }} .pagination .page-item {
        margin: 0;
    }
    
    #{{ $paginationId }} .pagination .page-link {
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        background-color: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        min-width: 2.5rem;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    #{{ $paginationId }} .pagination .page-link:hover {
        background-color: #f9fafb;
        border-color: #DC2626;
        color: #DC2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    #{{ $paginationId }} .pagination .page-item.active .page-link {
        background-color: #DC2626;
        border-color: #DC2626;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }
    
    #{{ $paginationId }} .pagination .page-item.active .page-link:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
        color: #ffffff;
        transform: translateY(-1px);
    }
    
    #{{ $paginationId }} .pagination .page-item.disabled .page-link {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #9CA3AF;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    #{{ $paginationId }} .pagination .page-item.disabled .page-link:hover {
        background-color: #f9fafb;
        border-color: #e5e7eb;
        color: #9CA3AF;
        transform: none;
        box-shadow: none;
    }
    
    #{{ $paginationId }} .pagination .page-item:first-child .page-link,
    #{{ $paginationId }} .pagination .page-item:last-child .page-link {
        font-weight: 600;
    }
</style>

