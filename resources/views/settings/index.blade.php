@extends('layouts.app')

@section('content')
    @if(Auth::user()->id == 7 || Auth::user()->id == 6)
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Settings</h1></div>
    </div>

    <form method="POST" action="/settings">
        @csrf
        <table class="table table-hover">
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
        @foreach ($settings as $service)
            <tr>
                <td>{{ $service->key }}</td>
                <td><input type="text" id="{{ $service->key }}" name="{{ $service->key }}" value="{{ $service->value }}"></td>
            </tr>
        @endforeach
    </table>
        <button type="submit" class="btn btn-success btn-submit">Submit</button>
    </form>
    @endif
@endsection

