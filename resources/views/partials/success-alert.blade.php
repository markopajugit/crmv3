@if ($message = Session::get('success'))
    <div class="alert alert-success mb-4" style="border-radius: 8px; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 1rem 1.25rem; background-color: #D1FAE5; color: #065F46; border-left: 4px solid #10B981;">
        <i class="fa-solid fa-check-circle" style="margin-right: 0.5rem;"></i> {{ $message }}
    </div>
@endif

