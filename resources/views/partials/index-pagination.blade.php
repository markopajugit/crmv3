@php
    // Required parameters:
    // $paginator - The paginator instance (e.g., $companies, $persons)
    // $paginationId - ID for pagination container (e.g., "companiesPagination", "personsPagination")
@endphp

<!-- Pagination -->
<div class="row">
    <div class="col-12" id="{{ $paginationId }}" style="display: flex; justify-content: center; align-items: center; padding: 2rem 0;">
        {{ $paginator->onEachSide(5)->links() }}
    </div>
</div>

