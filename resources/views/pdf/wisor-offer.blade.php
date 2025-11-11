<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            font-size: 12px;
            color: #000000;
        }

        .container {
            width: 892px;
            margin: 0 auto;
            background-size: cover;
            padding: 30px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            width: 50%;
        }

        .header .dates {
            width: 50%;
            text-align: right;
        }

        .invoice-number {
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
        }

        .address {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .recipient {
            width: 50%;
        }

        .account-details {
            width: 50%;
            text-align: right;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 5px;
            text-align: left;
        }

        table th {
            border-top: 1px solid black;
            color: #808080;
            font-weight: normal;
        }

        table tbody{
            border-bottom: 2px solid black;
        }

        .total {
            margin-top: 30px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #808080;
        }
        p {
            margin:3px 0;
        }
    </style>
</head>
<body>
<div style="margin-bottom: 20px;">
    <div style="float: left; width: 50%;">
        <img src="{{ asset('images/wisor-logo.jpg') }}" style="width:150px;" alt="logo">
    </div>
    <div style="float: right; width: 50%; padding-top: 50px;">
        <p style="font-size:16px; margin-bottom: 10px;"><b>Price offer {{ $data['invoice']['number'] }}</b></p>
        <div style="float: left; width: 35%;">
            <p>Date</p>
        </div>
        <div style="float: right; width: 65%;">
            <p>{{ $data['invoice']['issue_date'] }}</p>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float: left; width: 50%;">
        <p style="color:#808080;">Bill to:</p>
        <p style="font-size:14px; font-weight: bold;">To whom it may concern</p>
    </div>

    <div style="clear: both;"></div>
</div>

<div style="margin-bottom: 30px;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
        <tr>
            <th style="text-align: left; padding: 5px;">Description</th>
            <th style="text-align: left; padding: 5px;">Period</th>
            <th style="text-align: right; padding: 5px;">Sum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($services as $key => $service)
            <tr>
                <td>{{ $service['pivot']['name'] }}</td>
                <!--<td>@if ($service['type'] == 'Reaccuring') (Reaccuring {{ $service['reaccuring_frequency'] }}mo) @endif</td>-->
                <td>@if($service['pivot']['date_from']) {{$service['pivot']['date_from']}} @endif @if($service['pivot']['date_to']) - {{$service['pivot']['date_to']}} @endif</td>
                <td style="text-align: right;">{{ sprintf("%01.2f", $service['pivot']['cost']) }}€</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr style="font-size: 16px; font-weight: bold;">
            <td></td>
            <td style="padding: 5px;">Total</td>
            <td style="text-align: right; padding: 5px;">{{ $data['totals']['sum'] }}€</td>
        </tr>
        </tfoot>
    </table>
</div>

<div>
    <p style="text-align: center; margin-bottom: 15px;">VAT 0% as per Estonian Value-added Tax Act §15 section 4 p 1.</p>
</div>

<div class="footer" style="color: #808080; border-top: 1px solid #808080; bottom:40px; position: absolute; width: 100%;">
    <div style="float: left; width: 50%;">
        <p>Wisor Group OÜ</p>
        <p>Address: Kaarli pst 1,10142 Kesklinna linnaosa, Tallinn, Harju
            maakond,Estonia</p>
        <p>
            By continuing with the payment, you agree with our terms and
            conditions.
        </p>
    </div>
    <div style="float: right; text-align:right; width: 50%;">
        <p>Reg no: 14818750</p>
        <p>VAT ID: EE102195839</p>
    </div>
</div>
</body>
</html>
