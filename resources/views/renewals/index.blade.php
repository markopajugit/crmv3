@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Renewals</h1></div>
        <!--<div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('companies.create') }}" style="color:white;"> Create New Company</a></div>-->
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
            <th>Company</th>
            <th>Name</th>
            <th>Status</th>
            <th>Payment status</th>
            <th>Awaiting status</th>
            <th>Responsible</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($orders))
            @foreach ($orders as $order)
                @if($order)
                    <tr class="clickable" data-action="navigate" data-url="/orders/{{ $order->id }}">
                        <td>{{ $order->number }}</td>
                        <td>{{$order->company->name}}</td>
                        <td>{{ $order->name }}</td>
                        <td @if($order->status == 'In Progress') style="color: #ffd700; font-weight: bold;" @elseif($order->status == 'Not Active') style="color: #ffffff; font-weight: bold;" @elseif($order->status == 'Finished') style="color: #00ff88; font-weight: bold;" @endif>{{ $order->status }}</td>
                        <td @if($order->payment_status == 'Partially Paid') style="color: #ffd700; font-weight: bold;" @elseif($order->payment_status == 'Not Paid') style="color: #ffffff; font-weight: bold;" @elseif($order->payment_status == 'Paid') style="color: #00ff88; font-weight: bold;" @endif>{{ $order->payment_status }}</td>
                        <td @if($order->awaiting_status == 'Waiting action from us') style="color: #ffffff; font-weight: bold;" @elseif($order->awaiting_status == 'Waiting action from Client') style="color: #00ff88; font-weight: bold;" @else style="color: #ffd700; font-weight: bold;" @endif>{{ $order->awaiting_status }}</td>
                        <td><i class="fa-solid fa-user-tie"></i>{{ $order->responsible_user->name }}</td>
                        <td>{{ $order->description }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
@endsection
