@extends('layouts.app')

@section('content')
<style>
    /* Base Typography - Ensure Nunito font is used */
    body, .card, .panel, .modal, input, select, textarea, button, .btn {
        font-family: 'Nunito', sans-serif;
    }

    /* Form Styling */
    .form-label,
    strong {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    /* Style all inputs, selects, and textareas */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="date"],
    input[type="search"],
    input[type="password"],
    textarea,
    select,
    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.625rem 0.75rem;
        font-size: 0.875rem;
        color: #1f2937;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    input[type="search"]:focus,
    input[type="password"]:focus,
    textarea:focus,
    select:focus,
    .form-control:focus {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        outline: none;
    }

    input[type="text"]::placeholder,
    input[type="email"]::placeholder,
    input[type="tel"]::placeholder,
    input[type="number"]::placeholder,
    input[type="search"]::placeholder,
    input[type="password"]::placeholder,
    textarea::placeholder,
    .form-control::placeholder {
        color: #9CA3AF;
    }

    textarea,
    textarea.form-control {
        resize: vertical;
    }

    select,
    select.form-control {
        cursor: pointer;
    }

    /* Button Styling */
    .btn {
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: #DC2626;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #B91C1C;
    }

    .btn-success,
    .btn-submit {
        background-color: #10B981;
        color: #ffffff;
    }

    .btn-success:hover,
    .btn-submit:hover {
        background-color: #059669;
    }

    /* Alert Styling */
    .alert {
        border-radius: 6px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background-color: #FEF2F2;
        border-color: #FECACA;
        color: #991B1B;
    }

    .alert-danger ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }

    .print-error-msg {
        background-color: #FEF2F2;
        border-color: #FECACA;
        color: #991B1B;
    }

    /* Form Group Styling */
    .mb-3 {
        margin-bottom: 1rem;
    }

    /* Checkbox Styling */
    input[type="checkbox"] {
        width: auto;
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
</style>
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
