@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Companies</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('companies.create') }}" style="color:white;"> Create New Company</a></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-hover">
        <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Reg Code</th>
            <th>Reg Country</th>
            <th>VAT</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($companies as $company)
            <tr class="clickable" onclick="window.location='/companies/{{ $company->id }}';">
                <td>{{ $company->id}}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->registry_code }}</td>
                <td>{{ $company->registration_country }}</td>
                <td>{{ $company->vat }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $companies->onEachSide(5)->links() }}
@endsection
