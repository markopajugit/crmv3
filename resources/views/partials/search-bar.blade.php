@php
    // Required parameters:
    // $searchInputId - ID for search input (e.g., "companySearch", "personSearch")
    // $placeholder - Placeholder text for search input
@endphp

<!-- Search Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="padding: 1.25rem;">
                <div class="input-group" style="border-radius: 6px; border: 1px solid #d1d5db; overflow: hidden; background-color: #ffffff;">
                    <div class="input-group-prepend" style="border: none; margin: 0;">
                        <span class="input-group-text" style="background-color: #f9fafb; border: none; border-right: 1px solid #d1d5db; padding: 0.625rem 1rem; margin: 0;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #6B7280;"></i>
                        </span>
                    </div>
                    <input type="text" 
                           id="{{ $searchInputId }}" 
                           class="form-control" 
                           placeholder="{{ $placeholder }}" 
                           autocomplete="off"
                           style="border: none; padding: 0.625rem 1rem; font-size: 0.875rem; box-shadow: none; background-color: #ffffff; outline: none;">
                    <div class="input-group-append" style="border: none; margin: 0;">
                        <button class="btn btn-secondary" type="button" id="clearSearch" style="display: none; border: none; border-left: 1px solid #d1d5db; padding: 0.625rem 1rem; margin: 0; background-color: #6B7280; color: #ffffff; border-radius: 0;">
                            <i class="fa-solid fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

