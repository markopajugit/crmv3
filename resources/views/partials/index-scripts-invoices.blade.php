@php
    // Required parameters:
    // $searchInputId - ID for search input (e.g., "invoiceSearch")
    // $tableBodyId - ID for table body (e.g., "invoicesTableBody")
    // $paginationId - ID for pagination container (e.g., "invoicesPagination")
    // $indexRoute - Route name for index action (e.g., "invoices.index")
    // $countId - ID for count element (e.g., "invoicesCount")
    // $singular - Singular form of entity (e.g., "invoice")
    // $plural - Plural form of entity (e.g., "invoices")
@endphp

<script>
$(document).ready(function() {
    let searchTimeout;
    const $searchInput = $('#{{ $searchInputId }}');
    const $clearButton = $('#clearSearch');
    const $tableBody = $('#{{ $tableBodyId }}');
    const $pagination = $('#{{ $paginationId }}');
    const $filterType = $('#filterType');
    
    // Show/hide clear button
    $searchInput.on('input', function() {
        if ($(this).val().length > 0) {
            $clearButton.show();
        } else {
            $clearButton.hide();
        }
    });
    
    // Clear search
    $clearButton.on('click', function() {
        $searchInput.val('');
        $(this).hide();
        performSearch('', 1);
    });
    
    // Perform search with debounce
    $searchInput.on('keyup', function() {
        const searchTerm = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(function() {
            performSearch(searchTerm, 1); // Always reset to page 1 on new search
        }, 300); // Wait 300ms after user stops typing
    });
    
    // Handle filter changes
    $filterType.on('change', function() {
        const type = $(this).val();
        // Update URL if needed (for paid/unpaid views)
        if (type === 'paid') {
            window.history.pushState({}, '', '{{ route("paidInvoices") }}');
        } else if (type === 'unpaid') {
            window.history.pushState({}, '', '{{ route("unpaidInvoices") }}');
        } else {
            window.history.pushState({}, '', '{{ route("invoices.index") }}');
        }
        performSearch($searchInput.val().trim(), 1);
    });
    
    // Handle pagination clicks (for search results)
    $(document).on('click', '#{{ $paginationId }} .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const searchTerm = $searchInput.val().trim();
        
        // Extract page number from URL
        const pageMatch = url.match(/page=(\d+)/);
        const page = pageMatch ? pageMatch[1] : 1;
        
        performSearch(searchTerm, page);
    });
    
    function performSearch(searchTerm, page = 1) {
        // Show loading state
        $tableBody.html('<tr><td colspan="7" class="text-center py-4" style="padding: 3rem 1.25rem; color: #6B7280;"><i class="fa-solid fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block; color: #DC2626;"></i> <span style="font-size: 0.875rem;">Searching...</span></td></tr>');
        
        // Determine which route to use based on type filter
        let routeUrl = '{{ route($indexRoute) }}';
        const type = $filterType.val();
        if (type === 'paid') {
            routeUrl = '{{ route("paidInvoices") }}';
        } else if (type === 'unpaid') {
            routeUrl = '{{ route("unpaidInvoices") }}';
        }
        
        $.ajax({
            url: routeUrl,
            method: 'GET',
            data: {
                search: searchTerm,
                type: type,
                page: page,
                ajax: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.html) {
                    // The response.html is just the tbody content
                    $tableBody.html(response.html);
                }
                
                if (response.pagination) {
                    $pagination.html(response.pagination);
                }
                
                // Update total count
                if (response.total !== undefined) {
                    const currentSearchTerm = searchTerm.trim();
                    const countText = response.total !== 1 ? '{{ $plural }}' : '{{ $singular }}';
                    let countHtml = '<i class="fa-solid fa-database" style="margin-right: 0.25rem;"></i> Total: <strong style="color: #1f2937;">' + response.total + '</strong> ' + countText;
                    
                    // Show/hide search indicator
                    if (currentSearchTerm && currentSearchTerm.length > 0) {
                        countHtml += ' <span id="searchIndicator" style="color: #6B7280;">| Showing search results</span>';
                    }
                    
                    $('#{{ $countId }}').html(countHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                $tableBody.html('<tr><td colspan="7" class="text-center py-4" style="padding: 3rem 1.25rem; color: #EF4444;"><i class="fa-solid fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: 0.5rem; display: block;"></i> <span style="font-size: 0.875rem;">Error loading results. Please try again.</span></td></tr>');
            }
        });
    }
});
</script>

