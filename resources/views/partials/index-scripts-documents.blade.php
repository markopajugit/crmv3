@php
    // Required parameters:
    // $searchInputId - ID for search input (e.g., "documentSearch")
    // $tableBodyId - ID for table body (e.g., "documentsTableBody")
    // $paginationId - ID for pagination container (e.g., "documentsPagination")
    // $indexRoute - Route name for index (e.g., "documents.index")
    // $countId - ID for count display (e.g., "documentsCount")
    // $singular - Singular form of resource name (e.g., "document")
    // $plural - Plural form of resource name (e.g., "documents")
@endphp

<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#{{ $searchInputId }}');
    const clearButton = $('#clearSearch');
    const filterCategory = $('#filterCategory');
    const tableBody = $('#{{ $tableBodyId }}');
    const pagination = $('#{{ $paginationId }}');
    const countElement = $('#{{ $countId }}');

    // Show/hide clear button
    function toggleClearButton() {
        if (searchInput.val().length > 0) {
            clearButton.show();
        } else {
            clearButton.hide();
        }
    }

    // Initial state
    toggleClearButton();

    // Clear search
    clearButton.on('click', function() {
        searchInput.val('');
        toggleClearButton();
        performSearch();
    });

    // Search on input (with debounce)
    searchInput.on('input', function() {
        toggleClearButton();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 500);
    });

    // Search on Enter key
    searchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            performSearch();
        }
    });

    // Category filter change
    filterCategory.on('change', function() {
        performSearch();
    });

    // Perform search
    function performSearch() {
        const search = searchInput.val();
        const category = filterCategory.val();
        
        // Build URL with query parameters
        const url = new URL('{{ route($indexRoute) }}', window.location.origin);
        if (search) {
            url.searchParams.set('search', search);
        }
        if (category && category !== 'all') {
            url.searchParams.set('category', category);
        }
        url.searchParams.set('ajax', '1');

        // Show loading state
        tableBody.html('<tr><td colspan="5" class="text-center py-5"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</td></tr>');

        $.ajax({
            url: url.toString(),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                tableBody.html(response.html);
                pagination.html(response.pagination);
                countElement.text(response.total);
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                tableBody.html('<tr><td colspan="5" class="text-center py-5 text-danger">Error loading {{ $plural }}. Please try again.</td></tr>');
            }
        });
    }

    // Handle pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlObj = new URL(url, window.location.origin);
            urlObj.searchParams.set('ajax', '1');
            
            // Preserve search and category filters
            const search = searchInput.val();
            const category = filterCategory.val();
            if (search) {
                urlObj.searchParams.set('search', search);
            }
            if (category && category !== 'all') {
                urlObj.searchParams.set('category', category);
            }

            tableBody.html('<tr><td colspan="5" class="text-center py-5"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</td></tr>');

            $.ajax({
                url: urlObj.toString(),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    tableBody.html(response.html);
                    pagination.html(response.pagination);
                    countElement.text(response.total);
                    // Scroll to top of table
                    $('html, body').animate({
                        scrollTop: tableBody.offset().top - 100
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('Pagination error:', error);
                    tableBody.html('<tr><td colspan="5" class="text-center py-5 text-danger">Error loading {{ $plural }}. Please try again.</td></tr>');
                }
            });
        }
    });
});
</script>

