@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Persons</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('persons.create') }}" style="color:white;"> Create New Person</a></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-hover">
        <tr>
            <th>Name</th>
            <th>ID Code</th>
            <th>Address</th>
            <th>E-mail</th>
            <th>Phone</th>
        </tr>
        @foreach ($persons as $person)
            <tr class="clickable" data-action="navigate" data-url='/persons/{{ $person->id }}'>
                <td>{{ $person->name }}</td>
                <td>{{ $person->id_code }}
                    @if($person->country)({{ $person->country }})@endif
                    @if($person->id_code_est) - EST: {{$person->id_code_est}}@endif
                </td>
                <td>{{ $person->address }}</td>
                <td>{{ $person->email }}</td>
                <td>{{ $person->phone }}</td>
            </tr>
        @endforeach
    </table>

    {{ $persons->onEachSide(5)->links() }}
@endsection
