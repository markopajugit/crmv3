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
        <img src="{{ asset('images/logo.svg') }}" style="width:150px;" alt="logo">
    </div>
    <div style="float: right; width: 50%; padding-top: 50px;">
        <p style="font-size:16px;"><b>Advance payment invoice {{ $data['invoice']['number'] }}</b></p>
        <div style="float: left; width: 35%;">
            <p>Date</p>
            <p>Due Date</p>
        </div>
        <div style="float: right; width: 65%;">
            <p>{{ $data['invoice']['issue_date'] }}</p>
            <p>{{ $data['invoice']['payment_date'] }}</p>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float: left; width: 50%;">
        <p style="color:#808080;">Bill to:</p>
        @if($data['invoice']['payer_name'])
            <p style="font-size:14px; font-weight: bold;">{{$data['invoice']['payer_name']}}</p>
            @if(isset($data['invoice']['registry_code']))
                <p>Reg code: {{ $data['invoice']['registry_code'] }}</p>
            @endif
        @else
            <p style="font-size:14px; font-weight: bold;">{{ $data['company']['name'] }}</p>
            @if(isset($data['invoice']['registry_code']))
                <p>Reg code: {{ $data['invoice']['registry_code'] }}</p>
            @elseif(isset($data['company']['registry_code']))
                <!--<p>Reg code: {{ $data['company']['registry_code'] }}</p>-->
            @endif
        @endif
        @if($data['invoice']['street'])
            <p>{{ $data['invoice']['street'] }}</p>
        @else
            <!--<p>{{ $data['company']['address_street'] }}</p>-->
        @endif

        @if($data['invoice']['city'])
            <p>{{ $data['invoice']['city'] }}</p>
        @else
            <!--<p>{{ $data['company']['address_city'] }}</p>-->
        @endif

        @if($data['invoice']['zip'])
            <p>{{ $data['invoice']['zip'] }}</p>
        @else
            <!--<p>{{ $data['company']['address_zip'] }}</p>-->
        @endif

        @if($data['invoice']['country'])
            <p>{{ $data['invoice']['country'] }}</p>
        @else
            <!--<p>{{ $data['company']['address_dropdown'] }}</p>-->
        @endif

        @if($data['invoice']['vat_no'])
            <p>VAT ID: {{ $data['invoice']['vat_no'] }}</p>
        @elseif($data['company']['vat'])
            <!--<p>VAT ID: {{ $data['company']['vat'] }}</p>-->
        @endif

        <!--<p>{{ $data['company']['address_city'] }}</p>
        <p>{{ $data['company']['address_zip'] }}</p>
        <p>{{ $data['company']['address_dropdown'] }}</p>-->
    </div>
    <div style="float: right; width: 50%; font-weight: bold;">
        <p style="color:#808080;"><b>SEPA payment details</b></p>
        <div style="float: left; width: 35%;">
            <p>Recipient</p>
            <p>Account</p>
        </div>
        <div style="float: right; width: 65%;">
            <p>Wisor Group OÜ</p>
            <p>LT123500010008545129</p>
        </div>
        <div style="height:70px;"></div>
        <p style="color:#808080;"><b>SWIFT/BIC payment details</b></p>
        <div style="float: left; width: 35%;">
            <p>Recipient</p>
            <p>Account</p>
            <p>SWIFT/BIC</p>
            <p>Bank</p>
            <p>Bank address</p>
        </div>
        <div style="float: right; width: 65%;">
            <p>Wisor Group OÜ</p>
            <p>EE264204278619971400</p>
            <p>EKRDEE22</p>
            <p>Coop Pank AS</p>
            <p>Maakri 30, 15014 Tallinn, Estonia</p>
        </div>
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
        <tr>
            <td>{{ $data['company']['name'] }}</td>
            <td></td>
            <td></td>
        </tr>
        @foreach($services as $key => $service)
            @if(is_null($service['pivot']['cost']))
                <tr>
                    <td>{{ $service['pivot']['name'] }}</td>
                    <!--<td>@if ($service['type'] == 'Reaccuring') (Reaccuring {{ $service['reaccuring_frequency'] }}mo) @endif</td>-->
                    <td>
                        @if(!is_null($service['pivot']['date_from']) && !is_null($service['pivot']['date_to']))
                            {{$service['pivot']['date_from']}} - {{$service['pivot']['date_to']}}
                        @endif
                    </td>
                    <td style="text-align: right;">@if(!is_null($service['pivot']['cost'])){{ sprintf("%01.2f", $service['pivot']['cost']) }}€@endif</td>
                </tr>
            @endif
        @endforeach
        @foreach($services as $key => $service)
            @if(!is_null($service['pivot']['cost']))
                <tr>
                    <td>{{ $service['pivot']['name'] }}</td>
                    <!--<td>@if ($service['type'] == 'Reaccuring') (Reaccuring {{ $service['reaccuring_frequency'] }}mo) @endif</td>-->
                    <td>@if(!is_null($service['pivot']['date_from']) && !is_null($service['pivot']['date_to']))
                            {{$service['pivot']['date_from']}} - {{$service['pivot']['date_to']}}
                        @endif
                    </td>
                    <td style="text-align: right;">@if(!is_null($service['pivot']['cost'])){{ sprintf("%01.2f", $service['pivot']['cost']) }}€@endif</td>
                </tr>
            @endif
        @endforeach
        </tbody>
        <tfoot>
        @if($data['invoice']['vat'] == 20)
        <tr>
            <td></td>
            <td style="padding: 5px;">Net sum</td>
            <td style="text-align: right; padding: 5px;">{{ $data['totals']['sum'] }}€</td>
        </tr>
        <tr>
            <td></td>
            <td style="padding: 5px; border-bottom: 1px solid black;">VAT 20%</td>
            <td style="text-align: right; padding: 5px; border-bottom: 1px solid black;">{{ $data['totals']['vat'] }}€</td>
        </tr>
        @endif
        @if($data['invoice']['vat'] == 22)
            <tr>
                <td></td>
                <td style="padding: 5px;">Net sum</td>
                <td style="text-align: right; padding: 5px;">{{ $data['totals']['sum'] }}€</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding: 5px; border-bottom: 1px solid black;">VAT 22%</td>
                <td style="text-align: right; padding: 5px; border-bottom: 1px solid black;">{{ $data['totals']['vat'] }}€</td>
            </tr>
        @endif
        @if($data['invoice']['vat'] == 24)
            <tr>
                <td></td>
                <td style="padding: 5px;">Net sum</td>
                <td style="text-align: right; padding: 5px;">{{ $data['totals']['sum'] }}€</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding: 5px; border-bottom: 1px solid black;">VAT 24%</td>
                <td style="text-align: right; padding: 5px; border-bottom: 1px solid black;">{{ $data['totals']['vat'] }}€</td>
            </tr>
        @endif
        <tr style="font-size: 16px; font-weight: bold;">
            <td></td>
            <td style="padding: 5px;">Total</td>
            <td style="text-align: right; padding: 5px;">{{ $data['totals']['sumwithvat'] }}€</td>
        </tr>
        </tfoot>
    </table>
</div>

<div>
    <p style="text-align: center; margin-bottom: 15px;">VAT {{ $data['invoice']['vat'] }}% as per Estonian Value-added Tax Act §15 section 4 p 1.</p>
    <p style="text-align: center; margin: 5px 0;"><b>On payment order please refer to "Invoice {{ $data['invoice']['number'] }}".</b></p>
    <p style="text-align: center;">In case the invoice number is missing, we might not be able to connect your payment to your order.</p>
    <p style="text-align: center;">By paying you agree with the terms and conditions of Wisor Group OÜ.</p>
</div>

<div class="footer" style="color: #808080; border-top: 1px solid #808080; bottom:40px; position: absolute; width: 100%;">
    <div style="float: left; width: 50%;">
        <p>Wisor Group OÜ</p>
        <p>Address: Narva mnt 5, Tallinn 10117, Estonia</p>
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
