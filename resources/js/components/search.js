/**
 * Search autocomplete component
 * Handles search input with autocomplete functionality
 */
export default function initSearch() {
    if (typeof window.$ === 'undefined') {
        console.warn('jQuery is not loaded. Search functionality may not work.');
        return;
    }

    // Wait for DOM to be ready if jQuery is available
    const init = function() {
        const $searchInput = $('#search');
        const $searchResults = $('#searchResults');
        const $categorySelect = $('#categoryName');

        // Check if search elements exist on the page
        if ($searchInput.length === 0) {
            return;
        }

        const autocompleteRoute = $searchInput.data('autocomplete-route') || '/autocomplete';

        // Hide search results when clicking outside
        $(document).on('click', function(event) {
            const $target = $(event.target);
            if (!$target.closest('#searchResults').length && $searchResults.is(':visible')) {
                $searchResults.hide();
            }
        });

        // Handle search input keyup
        $searchInput.on('keyup', function() {
            const query = $(this).val();
            if (query !== '') {
                const token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: autocompleteRoute,
                    method: 'get',
                    data: {
                        s: query,
                        _token: token,
                        category: $categorySelect.val()
                    },
                    success: function(data) {
                        $searchResults.fadeIn();
                        $searchResults.html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                    }
                });
            } else {
                $searchResults.hide();
            }
        });
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        $(document).ready(init);
    } else {
        init();
    }
}

