@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Services</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('services.create') }}"> Create New Service</a></div>
    </div>

    <div class="accordion" id="dynamic-accordion">
        @foreach($service_categories as $category)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{$category->id}}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$category->id}}" aria-expanded="false" aria-controls="collapse-{{$category->id}}">
                        {{$category->name}}
                    </button>
                </h2>
                <div id="collapse-{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="heading-{{$category->id}}" data-bs-parent="#dynamic-accordion">
                    <div class="accordion-body">
                        @foreach ($category->services as $service)
                            <a style="margin-bottom:5px;" href="/services/{{ $service->id }}">{{ $service->name }} <span style="float:right;">{{ $service->cost }}eur</span>
                                @if($service->type == 'Reaccuring')
                                    Reaccuring - {{ $service->reaccuring_frequency }}mo
                                @endif
                            </a><br>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="row mb-4" style="padding-top: 20px;">
        <div class="col-6 col-sm-3"><h1>Service Categories</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('createCategory') }}"> Create New Service Category</a></div>
    </div>
    <table class="table table-hover">
        <tr>
            <th>Name</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach ($service_categories as $service)
            <tr class="clickable" onclick="window.location='/services/category/{{ $service->id }}';">
                <td>{{ $service->name }}</td>
                <td>{{ $service->type }}</td>
                <td>@if($service->type == 'Reaccuring')
                        {{ $service->reaccuring_frequency }} months
                    @endif</td>
                <td>{{ $service->cost }}</td>
                <td></td>
            </tr>
        @endforeach
    </table>



@endsection
