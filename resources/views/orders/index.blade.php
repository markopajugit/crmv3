@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Orders</h1></div>
        <!--<div class="col-6 col-sm-3 m-2"><a class="btn btn-success" href="{{ route('companies.create') }}" style="color:white;"> Create New Company</a></div>-->
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="filters">
        <h3>Filter by</h3>
        <form action="/orders">
            <label for="responsible">Responsible:</label>
            <select name="responsible" id="responsible">
                <option value="all" {{ request('responsible') == 'all' ? 'selected' : '' }}>All</option>
                <option value="2" {{ request('responsible') == '2' ? 'selected' : '' }}>Merle</option>
                <option value="3" {{ request('responsible') == '3' ? 'selected' : '' }}>Kristine</option>
                <option value="4" {{ request('responsible') == '4' ? 'selected' : '' }}>Armine</option>
                <option value="6" {{ request('responsible') == '6' ? 'selected' : '' }}>Maria</option>
                <option value="7" {{ request('responsible') == '7' ? 'selected' : '' }}>Raili</option>
            </select>

            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Finished" {{ request('status') == 'Finished' ? 'selected' : '' }}>Finished</option>
                <!--<option value="In progress" {{ request('status') == 'In progress' ? 'selected' : '' }}>In progress</option>-->
                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Not Active" {{ request('status') == 'Not Active' ? 'selected' : '' }}>Not Active</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="not started" {{ request('status') == 'not started' ? 'selected' : '' }}>Not Started</option>
            </select>

            <label for="payment_status">Payment status:</label>
            <select name="payment_status" id="payment_status">
                <option value="all" {{ request('payment_status') == 'all' ? 'selected' : '' }}>All</option>
                <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Partially paid" {{ request('payment_status') == 'Partially paid' ? 'selected' : '' }}>Partially paid</option>
                <option value="Not Paid" {{ request('payment_status') == 'Not Paid' ? 'selected' : '' }}>Not paid</option>
            </select>

            <button type="submit">Show</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>No</th>
            <th>Company</th>
            <th>Name</th>
            <th>Status</th>
            <th>Payment status</th>
            <th>Services Cost</th>
            <th>Responsible</th>
            <!--<th>Description</th>-->
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            @php
                $servicesTotalCost = 0;
            @endphp
            @foreach ($order->orderServices as $service)
                @php
                    $servicesTotalCost = $servicesTotalCost + $service->cost;
                @endphp
            @endforeach
            <tr class="clickable" onclick="window.location='/orders/{{ $order->id }}';">
                <td>{{ $order->number }}</td>
                <td>{{ $order->company->name ?? $order->person->name . ' (Person)' }}</td>
                <td>{{ $order->name }}</td>
                <td @if($order->status == 'Active') style="color: darkgoldenrod" @elseif($order->status == 'Not Active') style="color: red" @elseif($order->status == 'Finished') style="color: green" @endif>{{ $order->status }}</td>
                <td @if($order->payment_status == 'Partially Paid') style="color: darkgoldenrod" @elseif($order->payment_status == 'Not Paid') style="color: red" @elseif($order->payment_status == 'Paid') style="color: green" @endif>{{ $order->payment_status }}</td>
                <!--<td @if($order->awaiting_status == 'Waiting action from us') style="color: red" @elseif($order->awaiting_status == 'Waiting action from Client') style="color: green" @else style="color: darkgoldenrod" @endif>{{ $order->awaiting_status }}</td>-->
                <td>{{ $servicesTotalCost }} EUR</td>
                <td><i class="fa-solid fa-user-tie"></i>{{ $order->responsible_user->name }}</td>
                <!--<td>{{ $order->description }}</td>-->
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $orders->onEachSide(5)->links() }}
@endsection
