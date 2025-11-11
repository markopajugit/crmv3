@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Company</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('companies.index') }}">Back</a>
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

    <form action="{{ route('companies.update',$company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $company->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Registry code:</strong>
                    <input type="text" name="regcode" value="{{ $company->registry_code }}" class="form-control" placeholder="Reg code">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Country:</strong>
                    <input type="text" name="country" value="{{ $company->registration_country }}" class="form-control" placeholder="Country">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>VAT:</strong>
                    <input type="text" name="vat" value="{{ $company->vat }}" class="form-control" placeholder="VAT no">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Notes:</strong>
                    <textarea class="form-control" style="height:150px" name="notes" placeholder="Notes">{{ $company->notes }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>

    </form>

<script>
    console.log('[DEBUG] Company edit page script loaded - inline script');
    console.log('[DEBUG] Document ready state:', document.readyState);
    
    // Wait for DOM and scripts to load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[DEBUG] DOMContentLoaded fired on company edit page');
            if (window.initCompanyEdit) {
                console.log('[DEBUG] Calling window.initCompanyEdit()');
                window.initCompanyEdit();
            } else {
                console.warn('[DEBUG] window.initCompanyEdit is not available yet');
            }
        });
    } else {
        console.log('[DEBUG] DOM already ready, checking for initCompanyEdit');
        if (window.initCompanyEdit) {
            console.log('[DEBUG] Calling window.initCompanyEdit() immediately');
            window.initCompanyEdit();
        } else {
            console.warn('[DEBUG] window.initCompanyEdit is not available');
            // Try again after a short delay
            setTimeout(function() {
                if (window.initCompanyEdit) {
                    console.log('[DEBUG] Calling window.initCompanyEdit() after delay');
                    window.initCompanyEdit();
                } else {
                    console.error('[DEBUG] window.initCompanyEdit still not available - JS may need to be compiled');
                }
            }, 1000);
        }
    }
</script>
@endsection
