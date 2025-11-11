@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-6 col-sm-3"><h1>Invoices ({{$type}})</h1></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn" href="/invoices/paid" style="color:white;">View Paid invoices</a></div>
        <div class="col-6 col-sm-3 m-2"><a class="btn" href="/invoices/unpaid" style="color:white;">View Unpaid invoices</a></div>
        <div class="col-6 col-sm-3 m-2">
            <form id="searchForm" method="GET">
                <label for="searchInput">Search by payer name</label>
                <input type="text" id="searchInput" name="q" placeholder="Enter Payer...">
                <input type="submit" value="Search">
            </form>
        </div>
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
        @foreach ($invoices as $invoice)

            <tr class="clickable" onclick="window.location='/view/pdf/{{ $invoice->id }}';">
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->number }}</td>
                @if($invoice->order->company)
                    <td>{{ $invoice->order->company->name }}</td>
                @endif
                @if($invoice->order->person)
                    <td>{{ $invoice->order->person->name }}</td>
                @endif
                <td>{{ $invoice->issue_date }}</td>
                <td>{{ $invoice->payment_date }}</td>
                <td>{{ $invoice->vat }}</td>
                <td>{{ $invoice->order->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    var searchInput = document.getElementById('searchInput').value;
                    var url = '/invoices/' + encodeURIComponent(searchInput);
                    window.location.href = url;
                });
            }
        });
    </script>

    {{ $invoices->onEachSide(5)->links() }}
@endsection
