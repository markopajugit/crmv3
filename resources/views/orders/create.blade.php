@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Order</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('orders.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="newOrder" action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="row">
            <form>
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>

            <div class="mb-3">
                <label for="companyName" class="form-label">Company</label>
                <select name="clients" id="companyID">
                    @foreach ($companies as $company)
                        <option value="{{$company->id}}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nameID" class="form-label">Name</label>
                <input type="text" id="nameID" name="name" class="form-control" placeholder="Name" required="">
            </div>

            <div class="mb-3">
                <label for="descriptionID" class="form-label">Description</label>
                <textarea id="descriptionID" name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="notesID" class="form-label">Notes</label>
                <textarea id="notesID" name="notes" class="form-control"></textarea>
            </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Services:</strong><br>
                        @foreach ($services as $service)
                            <input type="checkbox" id="service{{ $service->id }}" name="services[]" value="{{ $service->id }}" @if($service->checked) checked @endif>
                            <label for="service{{ $service->id }}">{{ $service->name }} ({{ $service->cost }})</label><br>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-success btn-submit">Add</button>
            </form>
        </div>

    </form>

    <script type="text/javascript">



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#newOrder .btn-submit").click(function(){

            var company_id = $("#companyID").val();
            var name = $("#nameID").val();
            var description = $("#descriptionID").val();
            var notes = $("#notesID").val();

            $.ajax({
                type:'POST',
                url:"{{ route('orders.store') }}",
                data:{company_id:company_id, name:name, description:description, notes:notes},
                success:function(data){
                    if($.isEmptyObject(data.error)){
                        window.location.reload()
                    }else{
                        printErrorMsg(data.error);
                    }
                }
            });

        });

    </script>
@endsection
