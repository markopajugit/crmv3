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
                <h2>Add New Service</h2>
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
                    <form id="addNewService" action="{{ route('services.store') }}" method="POST">
                        @csrf
                        <table class="table">
                            <tbody>
                            <tr>
                                <td style="width:50%"><strong>Category:</strong></td>
                                <td>
                                    <select name="service_category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%"><strong>Name:</strong></td>
                                <td><input type="text" name="name" id="name" placeholder="Service Name"></td>
                            </tr>
                            <tr>
                                <td style="width:50%"><strong>Cost:</strong></td>
                                <td><input type="text" name="cost" id="cost" placeholder="Service Cost"></td>
                            </tr>
                            <tr>
                                <td style="width:50%"><strong>Type:</strong></td>
                                <!--<td><input type="text" name="type" id="type" placeholder="Service Type"></td>-->
                                <td>
                                    <select name="type" id="type">
                                        <option value="Regular">Regular</option>
                                        <option value="Reaccuring">Reaccuring</option>
                                    </select>
                                </td>
                            </tr>

                            <tr style="display: none;" id="reaccuring-frequency__select">
                                <td style="width:50%"><strong>Service Length:</strong></td>
                                <td>
                                    <select name="reaccuring_frequency" id="reaccuring_frequency">
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                </td>
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

        $('#type').on('change', function () {
            console.log($(this).val());
            if($(this).val() === "Reaccuring"){
                $('#reaccuring-frequency__select').show();
            } else {
                $('#reaccuring-frequency__select').hide();
            }
        });
    </script>
@endsection
