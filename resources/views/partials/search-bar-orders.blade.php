@php
    // Required parameters:
    // $searchInputId - ID for search input (e.g., "orderSearch")
    // $placeholder - Placeholder text for search input
    // $users - Collection of users for responsible filter
    // $currentResponsible - Current responsible filter value
    // $currentStatus - Current status filter value
    // $currentPaymentStatus - Current payment status filter value
@endphp

<!-- Expanded Search Bar for Orders -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="padding: 1.25rem;">
                <!-- Text Search -->
                <div class="mb-3">
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
                
                <!-- Filters Row -->
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="filterResponsible" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Responsible:</label>
                        <select id="filterResponsible" class="form-control" style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem;">
                            <option value="all" {{ $currentResponsible == 'all' ? 'selected' : '' }}>All</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $currentResponsible == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="filterStatus" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Status:</label>
                        <select id="filterStatus" class="form-control" style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem;">
                            <option value="all" {{ $currentStatus == 'all' ? 'selected' : '' }}>All</option>
                            <option value="Finished" {{ $currentStatus == 'Finished' ? 'selected' : '' }}>Finished</option>
                            <option value="Active" {{ $currentStatus == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Not Active" {{ $currentStatus == 'Not Active' ? 'selected' : '' }}>Not Active</option>
                            <option value="Cancelled" {{ $currentStatus == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="not started" {{ $currentStatus == 'not started' ? 'selected' : '' }}>Not Started</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="filterPaymentStatus" style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">Payment Status:</label>
                        <select id="filterPaymentStatus" class="form-control" style="font-size: 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; padding: 0.5rem;">
                            <option value="all" {{ $currentPaymentStatus == 'all' ? 'selected' : '' }}>All</option>
                            <option value="Paid" {{ $currentPaymentStatus == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Partially paid" {{ $currentPaymentStatus == 'Partially paid' ? 'selected' : '' }}>Partially paid</option>
                            <option value="Not Paid" {{ $currentPaymentStatus == 'Not Paid' ? 'selected' : '' }}>Not paid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

