@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h1>Hello {{ $user->name }}!</h1>
        </div>
    </div>

    @if(Auth::user()->id == 7)
    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Totals</div>
                    <div class="panel-heading__button">
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td style="width:50%"><strong>Companies:</strong></td>
                            <td>{{ $totals['companies'] }}</td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Persons:</strong></td>
                            <td>{{ $totals['persons'] }}</td>
                        </tr>
                        <tr>
                            <td style="width:50%"><strong>Orders:</strong></td>
                            <td>{{ $totals['orders'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if(!$notRecentlyUpdatedOrders->isEmpty() && Auth::user()->id == 7)
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">Not recently updated</div>
                    <div class="panel-heading__button">
                    </div>
                </div>
                <div class="panel-body">

                        <table class="table">
                            <tr>
                                <th>Order</th>
                                <th>Last updated</th>
                                <th>Responsible</th>
                            </tr>
                            @foreach($notRecentlyUpdatedOrders as $order)
                                <tr>
                                    <td style="width:50%"><strong><a href="/orders/{{ $order->id }}"><i class="fa-solid fa-file"></i>{{$order->name}}</a></strong></td>
                                    <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                                    <td><i class="fa-solid fa-user-tie"></i>{{ $order->responsible_user->name }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <div class="row">
        <div class="col">
            <div class="panel panel-default panel-details">
                <div class="panel-heading">
                    <div class="panel-heading__title">My orders</div>
                    <div class="panel-heading__button">
                    </div>
                </div>
                <div class="panel-body">

                    <table class="table">
                        <tr>
                            <th>Order</th>
                            <th>Created at</th>
                            <th>Status</th>
                            <th>Payment status</th>
                            <th>Services Cost</th>
                            <!--<th>Awaiting status</th>-->
                            <th>Relation</th>
                            <!--<th>Notes</th>-->
                        </tr>
                        @foreach($myOrdersNotPaid as $order)
                            @php
                                $servicesTotalCost = 0;
                            @endphp
                            @foreach ($order->orderServices as $service)
                                @php
                                    $servicesTotalCost = $servicesTotalCost + $service->cost;
                                @endphp
                            @endforeach
                            <tr>
                                <td><strong><a href="/orders/{{ $order->id }}"><i class="fa-solid fa-file"></i>{{$order->number}} - {{$order->name}}</a></strong></td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td @if($order->status == 'In Progress') style="color: darkgoldenrod" @elseif($order->status == 'Not Active') style="color: red" @elseif($order->status == 'Finished') style="color: green" @endif>{{ $order->status }}</td>
                                <td @if($order->payment_status == 'Partially Paid') style="color: darkgoldenrod" @elseif($order->payment_status == 'Not Paid') style="color: red" @elseif($order->payment_status == 'Paid') style="color: green" @endif>{{ $order->payment_status }}</td>
                                <!--<td @if($order->awaiting_status == 'Waiting action from us') style="color: red" @elseif($order->awaiting_status == 'Waiting action from Client') style="color: green" @else style="color: darkgoldenrod" @endif>{{ $order->awaiting_status }}</td>-->
                                <td>{{ $servicesTotalCost }}eur</td>
                                <td> @if($order->company) <a href="/companies/{{ $order->company->id }}"><i class="fa-solid fa-building"></i>{{ $order->company->name }} </a>@elseif($order->person)  <a href="/persons/{{ $order->person->id }}"><i class="fa-solid fa-user"></i>{{ $order->person->name }}</a>@endif</td>
                                <!--<td>{{ $order->description }}</td>-->
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
