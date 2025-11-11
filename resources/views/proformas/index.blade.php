@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Proformas</h1></div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Number</th>
            <th>Recepient</th>
            <th>Issue Date</th>
            <th>Payment Date</th>
            <th>VAT</th>
            <th>Order</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($proformas as $proforma)
            <tr class="clickable" onclick="window.location='/view/pdf/{{ $proforma->id }}';">
                <td>{{ $proforma->id }}</td>
                <td>{{ $proforma->number }}</td>
                @if($proforma->order->company)
                    <td>{{ $proforma->order->company->name }}</td>
                @endif
                @if($proforma->order->person)
                    <td>{{ $proforma->order->person->name }}</td>
                @endif
                <td>{{ $proforma->issue_date }}</td>
                <td>{{ $proforma->payment_date }}</td>
                <td>{{ $proforma->vat }}</td>
                <td>{{ $proforma->order->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $proformas->onEachSide(5)->links() }}
@endsection
