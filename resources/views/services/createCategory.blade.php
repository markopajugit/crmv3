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
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Service Category</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('services.index') }}"> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Details</div>
                    <div class="panel-heading__button">
                        <button type="button" class="btn saveDetails">
                            <i class="fa-solid fa-check"></i>Save
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="addNewService" action="{{ route('createCategory') }}" method="POST">
                        @csrf
                        <table class="table">
                            <tbody><tr>
                                <td style="width:50%"><strong>Name:</strong></td>
                                <td><input type="text" name="name" id="name" placeholder="Service Category Name"></td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $('.saveDetails').on('click', function(){
            $('#addNewService').submit();
        });
    </script>
@endsection
