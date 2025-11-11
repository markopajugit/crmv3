@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1><i class="fa-solid fa-user-tie"></i><input type="text" id="insertedName" placeholder="Name"></h1>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">

                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody><tr>
                            <td style="width:50%"><strong>E-mail:</strong></td>
                            <td><input type="text" id="insertedEmail" placeholder="E-mail"></td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Password:</strong></td>
                            <td><input type="password" id="insertedPassword" placeholder="Password"></td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Confirm password:</strong></td>
                            <td><input type="password" id="insertedConfirm_password" placeholder="Confirm Password"></td>
                        </tr>

                        </tbody></table>
                </div>
            </div>
        </div>

    </div>

    <form id="addNewPerson" action="{{ route('users.store') }}" method="POST">
        @csrf
        <input type="hidden" name="name" id="name">
        <input type="hidden" name="email" id="email">
        <input type="hidden" name="password" id="password">
        <input type="hidden" name="confirm_password" id="confirm_password">
    </form>

    <button type="button" class="btn saveNewUser" style="margin-top: 50px;">
        <i class="fa-solid fa-check"></i>Add
    </button>


