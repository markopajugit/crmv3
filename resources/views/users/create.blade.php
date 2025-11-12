@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 2rem;">
            <i class="fa-solid fa-user-tie" style="color: #DC2626; margin-right: 0.5rem;"></i> Create New User
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <div class="card-header" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem;">
                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1f2937;">
                    <i class="fa-solid fa-info-circle" style="color: #DC2626; margin-right: 0.5rem;"></i> User Details
                </h3>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <form action="{{ route('users.store') }}" method="POST" id="userCreateForm">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label for="name" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Name <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               placeholder="Enter user name"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem; font-size: 0.875rem; width: 100%;">
                        @error('name')
                            <div class="text-danger" style="font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="email" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter email address"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem; font-size: 0.875rem; width: 100%;">
                        @error('email')
                            <div class="text-danger" style="font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-lock" style="color: #DC2626; margin-right: 0.5rem;"></i> Password <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Enter password"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem; font-size: 0.875rem; width: 100%;">
                        @error('password')
                            <div class="text-danger" style="font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="confirm_password" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-lock" style="color: #DC2626; margin-right: 0.5rem;"></i> Confirm Password <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               placeholder="Confirm password"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem; font-size: 0.875rem; width: 100%;">
                        @error('confirm_password')
                            <div class="text-danger" style="font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary" style="background-color: #DC2626; border-color: #DC2626; padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 6px;">
                            <i class="fa-solid fa-check" style="margin-right: 0.5rem;"></i> Create User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-left: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 6px; background-color: #6B7280; border-color: #6B7280;">
                            <i class="fa-solid fa-times" style="margin-right: 0.5rem;"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
