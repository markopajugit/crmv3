<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        {!! file_get_contents(resource_path('views/pdf/css/invoice.css')) !!}
    </style>
</head>
<body>
<div class="invoice-header">
    <div class="invoice-header-left">
        <img src="{{ asset('images/logo.svg') }}" style="width:150px;" alt="logo">
    </div>
    <div class="invoice-header-right">
        <p class="invoice-title" style="margin-bottom: 10px;"><b>Price offer {{ $data['invoice']['number'] }}</b></p>
        <div class="invoice-dates-left">
            <p>Date</p>
        </div>
        <div class="invoice-dates-right">
            <p>{{ $data['invoice']['issue_date'] }}</p>
        </div>
    </div>
    <div class="invoice-header-clear"></div>
</div>

<div>
    <div class="recipient">
        <p class="invoice-bill-to">Bill to:</p>
        <p class="invoice-bill-to-name">To whom it may concern</p>
    </div>

    <div class="invoice-header-clear"></div>
</div>

<div style="margin-bottom: 30px;">
    <table class="invoice-table">
        <thead>
        <tr>
            <th>Description</th>
            <th>Period</th>
            <th class="text-right">Sum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $key => $service)
            <tr>
                <td>{{ $service['pivot']['name'] }}</td>
                <!--<td>@if ($service['type'] == 'Reaccuring') (Reaccuring {{ $service['reaccuring_frequency'] }}mo) @endif</td>-->
                <td>@if($service['pivot']['date_from']) {{$service['pivot']['date_from']}} @endif @if($service['pivot']['date_to']) - {{$service['pivot']['date_to']}} @endif</td>
                <td class="text-right">{{ sprintf("%01.2f", $service['pivot']['cost']) }}€</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr class="total-row">
            <td></td>
            <td>Total</td>
            <td class="text-right">{{ $data['totals']['sum'] }}€</td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="invoice-text-center">
    <p class="margin-bottom">VAT 0% as per Estonian Value-added Tax Act §15 section 4 p 1.</p>
</div>

<div class="invoice-footer">
    <div class="invoice-footer-left">
        <p>xxxxxxx</p>
        <p>Address: xxxxxxxxxxxx</p>
        <p>
            By continuing with the payment, you agree with our terms and
            conditions.
        </p>
    </div>
    <div class="invoice-footer-right">
        <p>Reg no: xxxxxxxxxx</p>
        <p>VAT ID: xxxxxxxxxx</p>
    </div>
</div>
</body>
</html>
