@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Users</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('users.create') }}" style="color:white;"> Create New User</a></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-mail</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr class="clickable" data-action="navigate" data-url='/users/{{ $user->id }}'>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
