@extends('layouts.app')

@section('content')

    <style>
        /* Tooltip container */
        .btn__disabled {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        /* Tooltip text */
        .btn__disabled .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: black;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            top: -5px;
            left: 105%;
            position: absolute;
            z-index: 1;
        }

        .btn__disabled:hover .tooltiptext {
            visibility: visible;
        }
    </style>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1><i class="fa-solid fa-user-tie"></i>{{ $user->name }}</h1>
            <h5>{{ $user->email }}</h5>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <button type="button" class="btn changePassword" data-coreui-toggle="modal" data-coreui-target="#changeUserPassword">
                <i class="fa-solid fa-pen-to-square"></i>Change Password
            </button>

            @if($user->orders->isEmpty())
                <button type="button" class="btn deleteUser">
                    <i class="fa-solid fa-trash"></i>Delete User
                </button>
            @else
                <button type="button" class="btn btn__disabled">
                    <span class="tooltiptext">Delete related data before deleting user</span>
                    <i class="fa-solid fa-trash"></i>Delete User
                </button>
            @endif
        </div>
    </div>


    <!-- Modals -->
    <div class="modal fade" id="changeUserPassword" data-coreui-backdrop="static" data-coreui-keyboard="false"
         tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Change password</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" id="userID" class="form-control" value="{{ $user->id }}">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="mb-3">
                            <label for="userName" class="form-label">User</label>
                            <input type="text" id="userName" class="form-control" value="{{ $user->name }}"
                                   disabled>
                        </div>

                        <div class="mb-3">

                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" id="new_password" class="form-control">
                            <label for="new_confirm_password" class="form-label">Confirm new password</label>
                            <input type="password" id="new_confirm_password" class="form-control">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="closeModal" type="button" class="btn btn-secondary" data-coreui-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-success btn-submit" id="confirmNewPassword">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        //Delete relatedPerson
        $('.deleteUser').on('click', function (e) {

            e.preventDefault();
            if (window.confirm("Delete User?")) {

                var userId = {{ $user->id }};

                console.log(userId);

                $.ajax({
                    type: 'DELETE',
                    url: "/users/"+userId,
                    success: function (data) {
                        window.location.replace("/users");
                    }

                });
            }
        });


        $('#confirmNewPassword').on('click', function (e) {
            var userId = {{ $user->id }};
            var new_password = $('#new_password').val();
            var new_confirm_password = $('#new_confirm_password').val();

            $.ajax({
                type: 'PUT',
                url: "/users/"+userId,
                data: {new_password: new_password, new_confirm_password: new_confirm_password},
                success: function (data){
                    if ($.isEmptyObject(data.error)) {
                        window.location.reload();
                    } else {
                        printErrorMsg(data.error);
                    }
                }

            });
        });

    </script>
@endsection
