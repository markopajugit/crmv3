@php
    // Required parameters:
    // $serviceSearchInputId - ID for service search input (e.g., "serviceSearch")
    // $serviceTableBodyId - ID for service table body (e.g., "servicesTableBody")
    // $servicePaginationId - ID for service pagination container (e.g., "servicesPagination")
    // $serviceCountId - ID for service count display (e.g., "servicesCount")
    // $categorySearchInputId - ID for category search input (e.g., "categorySearch")
    // $categoryTableBodyId - ID for category table body (e.g., "categoriesTableBody")
    // $categoryPaginationId - ID for category pagination container (e.g., "categoriesPagination")
    // $categoryCountId - ID for category count display (e.g., "categoriesCount")
    // $indexRoute - Route name for index (e.g., "services.index")
@endphp

<script>
$(document).ready(function() {
    let serviceSearchTimeout;
    let categorySearchTimeout;
    
    // Services search elements
    const $serviceSearchInput = $('#{{ $serviceSearchInputId }}');
    const $serviceSearchContainer = $serviceSearchInput.closest('.input-group');
    const $serviceClearButton = $serviceSearchContainer.find('#clearSearch');
    const $serviceTableBody = $('#{{ $serviceTableBodyId }}');
    const $servicePagination = $('#{{ $servicePaginationId }}');
    const $serviceCount = $('#{{ $serviceCountId }}');
    
    // Categories search elements
    const $categorySearchInput = $('#{{ $categorySearchInputId }}');
    const $categorySearchContainer = $categorySearchInput.closest('.input-group');
    const $categoryClearButton = $categorySearchContainer.find('#clearSearch');
    const $categoryTableBody = $('#{{ $categoryTableBodyId }}');
    const $categoryPagination = $('#{{ $categoryPaginationId }}');
    const $categoryCount = $('#{{ $categoryCountId }}');
    
    // Services search functions
    function toggleServiceClearButton() {
        if ($serviceSearchInput.val().length > 0) {
            $serviceClearButton.show();
        } else {
            $serviceClearButton.hide();
        }
    }
    
    $serviceSearchInput.on('input', function() {
        toggleServiceClearButton();
        clearTimeout(serviceSearchTimeout);
        serviceSearchTimeout = setTimeout(function() {
            performServiceSearch('', 1);
        }, 300);
    });
    
    $serviceSearchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            performServiceSearch('', 1);
        }
    });
    
    $serviceClearButton.on('click', function() {
        $serviceSearchInput.val('');
        toggleServiceClearButton();
        performServiceSearch('', 1);
    });
    
    // Categories search functions
    function toggleCategoryClearButton() {
        if ($categorySearchInput.val().length > 0) {
            $categoryClearButton.show();
        } else {
            $categoryClearButton.hide();
        }
    }
    
    $categorySearchInput.on('input', function() {
        toggleCategoryClearButton();
        clearTimeout(categorySearchTimeout);
        categorySearchTimeout = setTimeout(function() {
            performCategorySearch('', 1);
        }, 300);
    });
    
    $categorySearchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            performCategorySearch('', 1);
        }
    });
    
    $categoryClearButton.on('click', function() {
        $categorySearchInput.val('');
        toggleCategoryClearButton();
        performCategorySearch('', 1);
    });
    
    // Initial state
    toggleServiceClearButton();
    toggleCategoryClearButton();
    
    // Perform services search
    function performServiceSearch(searchTerm, page = 1) {
        const search = searchTerm || $serviceSearchInput.val().trim();
        const categorySearch = $categorySearchInput.val().trim();
        
        // Get current category page from URL or default to 1
        const currentCategoryPage = new URLSearchParams(window.location.search).get('categories_page') || 1;
        
        $serviceTableBody.html('<tr><td colspan="3" class="text-center py-5"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        $.ajax({
            url: '{{ route($indexRoute) }}',
            method: 'GET',
            data: {
                service_search: search,
                category_search: categorySearch,
                services_page: page,
                categories_page: currentCategoryPage,
                ajax: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.services_html) {
                    $serviceTableBody.html(response.services_html);
                }
                if (response.services_pagination) {
                    $servicePagination.html(response.services_pagination);
                }
                if (response.services_total !== undefined) {
                    const countText = response.services_total !== 1 ? 'services' : 'service';
                    let countHtml = '<i class="fa-solid fa-database" style="margin-right: 0.25rem;"></i> Total: <strong style="color: #1f2937;">' + response.services_total + '</strong> ' + countText;
                    if (search && search.length > 0) {
                        countHtml += ' <span id="searchIndicator" style="color: #6B7280;">| Showing search results</span>';
                    }
                    $serviceCount.html(countHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Service search error:', error);
                $serviceTableBody.html('<tr><td colspan="3" class="text-center py-5 text-danger">Error loading services. Please try again.</td></tr>');
            }
        });
    }
    
    // Perform categories search
    function performCategorySearch(searchTerm, page = 1) {
        const search = searchTerm || $categorySearchInput.val().trim();
        const serviceSearch = $serviceSearchInput.val().trim();
        
        // Get current service page from URL or default to 1
        const currentServicePage = new URLSearchParams(window.location.search).get('services_page') || 1;
        
        $categoryTableBody.html('<tr><td colspan="3" class="text-center py-5"><i class="fa-solid fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        $.ajax({
            url: '{{ route($indexRoute) }}',
            method: 'GET',
            data: {
                service_search: serviceSearch,
                category_search: search,
                services_page: currentServicePage,
                categories_page: page,
                ajax: true
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.categories_html) {
                    $categoryTableBody.html(response.categories_html);
                }
                if (response.categories_pagination) {
                    $categoryPagination.html(response.categories_pagination);
                }
                if (response.categories_total !== undefined) {
                    const countText = response.categories_total !== 1 ? 'categories' : 'category';
                    let countHtml = '<i class="fa-solid fa-database" style="margin-right: 0.25rem;"></i> Total: <strong style="color: #1f2937;">' + response.categories_total + '</strong> ' + countText;
                    if (search && search.length > 0) {
                        countHtml += ' <span id="searchIndicator" style="color: #6B7280;">| Showing search results</span>';
                    }
                    $categoryCount.html(countHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Category search error:', error);
                $categoryTableBody.html('<tr><td colspan="3" class="text-center py-5 text-danger">Error loading categories. Please try again.</td></tr>');
            }
        });
    }
    
    // Handle services pagination clicks
    $(document).on('click', '#{{ $servicePaginationId }} .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlObj = new URL(url, window.location.origin);
            const page = urlObj.searchParams.get('services_page') || 1;
            performServiceSearch('', page);
        }
    });
    
    // Handle categories pagination clicks
    $(document).on('click', '#{{ $categoryPaginationId }} .pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlObj = new URL(url, window.location.origin);
            const page = urlObj.searchParams.get('categories_page') || 1;
            performCategorySearch('', page);
        }
    });
});
</script>

