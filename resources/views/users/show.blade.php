@extends('layouts.app')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-user-tie" style="color: #DC2626; margin-right: 0.5rem;"></i> {{ $user->name }}
                </h1>
                <p style="font-size: 1rem; color: #6B7280; margin-bottom: 0;">
                    <i class="fa-solid fa-envelope" style="margin-right: 0.5rem;"></i> {{ $user->email }}
                </p>
            </div>
            <div>
                <a href="{{ route('users.index') }}" class="btn btn-secondary" style="margin-right: 0.5rem; padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 6px; background-color: #6B7280; border-color: #6B7280;">
                    <i class="fa-solid fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<!-- User Details Card -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <div class="card-header" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem;">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: #1f2937;">
                        <i class="fa-solid fa-info-circle" style="color: #DC2626; margin-right: 0.5rem;"></i> User Details
                    </h3>
                    <div>
                        <button type="button" class="btn btn-sm btn-primary" data-coreui-toggle="modal" data-coreui-target="#changeUserPassword" style="background-color: #DC2626; border-color: #DC2626; margin-right: 0.5rem;">
                            <i class="fa-solid fa-key" style="margin-right: 0.5rem;"></i> Change Password
                        </button>
                        @if($user->orders->isEmpty())
                            <button type="button" class="btn btn-sm btn-danger deleteUser" style="background-color: #EF4444; border-color: #EF4444;">
                                <i class="fa-solid fa-trash" style="margin-right: 0.5rem;"></i> Delete User
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-secondary" disabled style="background-color: #9CA3AF; border-color: #9CA3AF; cursor: not-allowed;" title="Cannot delete user with associated orders">
                                <i class="fa-solid fa-trash" style="margin-right: 0.5rem;"></i> Delete User
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding: 1.5rem;">
                <table class="table" style="margin-bottom: 0;">
                    <tbody>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-hashtag" style="color: #DC2626; margin-right: 0.5rem;"></i> ID
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                {{ $user->id }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> Name
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-envelope" style="color: #DC2626; margin-right: 0.5rem;"></i> E-mail
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                @if($user->email)
                                    <a href="mailto:{{ $user->email }}" style="color: #DC2626; text-decoration: none;">{{ $user->email }}</a>
                                @else
                                    <span style="color: #9CA3AF;">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-calendar" style="color: #DC2626; margin-right: 0.5rem;"></i> Created At
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                {{ $user->created_at ? $user->created_at->format('d.m.Y H:i') : '-' }}
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-calendar-check" style="color: #DC2626; margin-right: 0.5rem;"></i> Updated At
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                {{ $user->updated_at ? $user->updated_at->format('d.m.Y H:i') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%; padding: 1rem 0; font-weight: 600; color: #374151; vertical-align: middle;">
                                <i class="fa-solid fa-list" style="color: #DC2626; margin-right: 0.5rem;"></i> Associated Orders
                            </td>
                            <td style="width: 60%; padding: 1rem 0; color: #1f2937; vertical-align: middle;">
                                <span class="badge" style="background-color: #DC2626; color: white; padding: 0.375rem 0.75rem; border-radius: 4px; font-weight: 600;">
                                    {{ $user->orders->count() }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changeUserPassword" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 8px; border: 1px solid #e5e7eb;">
            <div class="modal-header" style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem;">
                <h5 class="modal-title" id="changePasswordModalLabel" style="font-weight: 600; color: #1f2937;">
                    <i class="fa-solid fa-key" style="color: #DC2626; margin-right: 0.5rem;"></i> Change Password
                </h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body" style="padding: 1.5rem;">
                    <input type="hidden" id="userID" value="{{ $user->id }}">
                    
                    <div class="alert alert-danger print-error-msg" style="display:none; border-radius: 6px; background-color: #FEE2E2; border-color: #FCA5A5; color: #991B1B;">
                        <ul style="margin-bottom: 0;"></ul>
                    </div>

                    <div class="form-group mb-4">
                        <label for="userName" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-user" style="color: #DC2626; margin-right: 0.5rem;"></i> User
                        </label>
                        <input type="text" 
                               id="userName" 
                               class="form-control" 
                               value="{{ $user->name }}" 
                               disabled
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem; background-color: #f9fafb; color: #6B7280;">
                    </div>

                    <div class="form-group mb-4">
                        <label for="new_password" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-lock" style="color: #DC2626; margin-right: 0.5rem;"></i> New Password <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="password" 
                               id="new_password" 
                               class="form-control" 
                               required
                               placeholder="Enter new password"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem;">
                    </div>

                    <div class="form-group mb-4">
                        <label for="new_confirm_password" style="font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: block;">
                            <i class="fa-solid fa-lock" style="color: #DC2626; margin-right: 0.5rem;"></i> Confirm New Password <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="password" 
                               id="new_confirm_password" 
                               class="form-control" 
                               required
                               placeholder="Confirm new password"
                               style="border: 1px solid #d1d5db; border-radius: 6px; padding: 0.625rem 1rem;">
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal" style="padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 6px; background-color: #6B7280; border-color: #6B7280;">
                        <i class="fa-solid fa-times" style="margin-right: 0.5rem;"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary btn-submit" id="confirmNewPassword" style="background-color: #DC2626; border-color: #DC2626; padding: 0.625rem 1.5rem; font-weight: 600; border-radius: 6px;">
                        <i class="fa-solid fa-check" style="margin-right: 0.5rem;"></i> Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function printErrorMsg(msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'block');
        if (typeof msg === 'object') {
            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        } else {
            $(".print-error-msg").find("ul").append('<li>' + msg + '</li>');
        }
    }

    // Delete User
    $('.deleteUser').on('click', function (e) {
        e.preventDefault();
        if (window.confirm("Are you sure you want to delete this user?")) {
            var userId = {{ $user->id }};
            
            $.ajax({
                type: 'DELETE',
                url: "/users/" + userId,
                success: function (data) {
                    if (data.success) {
                        window.location.replace("/users");
                    } else {
                        alert(data.error || "Error deleting user. Please try again.");
                    }
                },
                error: function(xhr, status, error) {
                    var errorMsg = "Error deleting user. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    alert(errorMsg);
                }
            });
        }
    });

    // Change Password
    $('#confirmNewPassword').on('click', function (e) {
        e.preventDefault();
        
        var userId = {{ $user->id }};
        var new_password = $('#new_password').val();
        var new_confirm_password = $('#new_confirm_password').val();
        
        // Hide previous errors
        $(".print-error-msg").css('display', 'none');
        
        // Basic validation
        if (!new_password || !new_confirm_password) {
            printErrorMsg({password: 'Both password fields are required.'});
            return;
        }
        
        if (new_password !== new_confirm_password) {
            printErrorMsg({password: 'Passwords do not match.'});
            return;
        }

        $.ajax({
            type: 'PUT',
            url: "/users/" + userId,
            data: {
                new_password: new_password, 
                new_confirm_password: new_confirm_password
            },
            success: function (data) {
                if ($.isEmptyObject(data.error)) {
                    // Close modal and reload
                    $('#changeUserPassword').modal('hide');
                    window.location.reload();
                } else {
                    printErrorMsg(data.error);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    printErrorMsg(xhr.responseJSON.error);
                } else {
                    printErrorMsg('An error occurred. Please try again.');
                }
            }
        });
    });

    // Reset form when modal is closed
    $('#changeUserPassword').on('hidden.coreui.modal', function () {
        $('#changePasswordForm')[0].reset();
        $(".print-error-msg").css('display', 'none');
    });
</script>
@endpush
