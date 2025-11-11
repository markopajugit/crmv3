<!DOCTYPE html>
<html>
<head>
    <title>Laravel 9 File Upload Example - Tutsmake.com</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">


</head>
<body>

<div class="container mt-4">
    @error('file')
    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
    @enderror
    <h2 class="text-center">File Upload in Laravel 9 - Tutsmake.com</h2>

    <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ url('store') }}" >
        @csrf
        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <input type="file" name="file" placeholder="Choose file" id="file">
                    @error('file')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
            </div>
        </div>
    </form>
</div>

</div>
</body>
</html>
