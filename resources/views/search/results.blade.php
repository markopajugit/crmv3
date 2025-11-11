@extends('layouts.app')

@section('content')

    @if(count($companies) > 0)
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Companies</h2>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Reg Code</th>
                <th>Reg Country</th>
                <th>Vat</th>
                <th width="280px">Action</th>
            </tr>
            @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->registry_code }}</td>
                    <td>{{ $company->registration_country }}</td>
                    <td>{{ $company->vat }}</td>
                    <td>
                        <form action="{{ route('companies.destroy',$company->id) }}" method="POST">

                            <a class="btn btn-info" href="{{ route('companies.show',$company->id) }}">Show</a>

                            <a class="btn btn-primary" href="{{ route('companies.edit',$company->id) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <!--<button type="submit" class="btn btn-danger">Delete</button>-->
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    @if(count($persons) > 0)
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Persons</h2>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Reg Code</th>
                <th>Reg Country</th>
                <th>Vat</th>
                <th width="280px">Action</th>
            </tr>
            @foreach ($persons as $person)
                <tr>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->address }}</td>
                    <td>{{ $person->id_code }}</td>
                    <td>{{ $person->email }}</td>
                    <td>{{ $person->phone }}</td>
                    <td>
                        <form action="{{ route('persons.destroy',$person->id) }}" method="POST">

                            <a class="btn btn-info" href="{{ route('persons.show',$person->id) }}">Show</a>

                            <!--<a class="btn btn-primary" href="{{ route('persons.edit',$person->id) }}">Edit</a>-->

                            @csrf
                            @method('DELETE')

                            <!--<button type="submit" class="btn btn-danger">Delete</button>-->
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif



@endsection
