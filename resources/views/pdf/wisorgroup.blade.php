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
        <p class="invoice-title"><b>Advance payment invoice {{ $data['invoice']['number'] }}</b></p>
        <div class="invoice-dates-left">
            <p>Date</p>
            <p>Due Date</p>
        </div>
        <div class="invoice-dates-right">
            <p>{{ $data['invoice']['issue_date'] }}</p>
            <p>{{ $data['invoice']['payment_date'] }}</p>
        </div>
    </div>
    <div class="invoice-header-clear"></div>
</div>

<div>
    <div class="recipient">
        <p class="invoice-bill-to">Bill to:</p>
        @if($data['invoice']['payer_name'])
            <p class="invoice-bill-to-name">{{$data['invoice']['payer_name']}}</p>
            @if(isset($data['invoice']['registry_code']))
                <p>Reg code: {{ $data['invoice']['registry_code'] }}</p>
            @endif
        @else
            <p class="invoice-bill-to-name">{{ $data['company']['name'] }}</p>
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
    <div class="invoice-payment-section">
        <p class="invoice-payment-title"><b>SEPA payment details</b></p>
        <div class="invoice-payment-left">
            <p>Recipient</p>
            <p>Account</p>
        </div>
        <div class="invoice-payment-right">
            <p>xxxxxxxxxxxx</p>
            <p>xxxxxxxxxxxxxxxxxx</p>
        </div>
        <div class="invoice-payment-spacer"></div>
        <p class="invoice-payment-title"><b>SWIFT/BIC payment details</b></p>
        <div class="invoice-payment-left">
            <p>Recipient</p>
            <p>Account</p>
            <p>SWIFT/BIC</p>
            <p>Bank</p>
            <p>Bank address</p>
        </div>
        <div class="invoice-payment-right">
            <p>Wisor Group OÜ</p>
            <p>EE264204278619971400</p>
            <p>EKRDEE22</p>
            <p>Coop Pank AS</p>
            <p>Maakri 30, 15014 Tallinn, Estonia</p>
        </div>
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
                    <td class="text-right">@if(!is_null($service['pivot']['cost'])){{ sprintf("%01.2f", $service['pivot']['cost']) }}€@endif</td>
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
                    <td class="text-right">@if(!is_null($service['pivot']['cost'])){{ sprintf("%01.2f", $service['pivot']['cost']) }}€@endif</td>
                </tr>
            @endif
        @endforeach
        </tbody>
        <tfoot>
        @if($data['invoice']['vat'] == 20)
        <tr>
            <td></td>
            <td>Net sum</td>
            <td class="text-right">{{ $data['totals']['sum'] }}€</td>
        </tr>
        <tr>
            <td></td>
            <td class="border-bottom">VAT 20%</td>
            <td class="text-right border-bottom">{{ $data['totals']['vat'] }}€</td>
        </tr>
        @endif
        @if($data['invoice']['vat'] == 22)
            <tr>
                <td></td>
                <td>Net sum</td>
                <td class="text-right">{{ $data['totals']['sum'] }}€</td>
            </tr>
            <tr>
                <td></td>
                <td class="border-bottom">VAT 22%</td>
                <td class="text-right border-bottom">{{ $data['totals']['vat'] }}€</td>
            </tr>
        @endif
        @if($data['invoice']['vat'] == 24)
            <tr>
                <td></td>
                <td>Net sum</td>
                <td class="text-right">{{ $data['totals']['sum'] }}€</td>
            </tr>
            <tr>
                <td></td>
                <td class="border-bottom">VAT 24%</td>
                <td class="text-right border-bottom">{{ $data['totals']['vat'] }}€</td>
            </tr>
        @endif
        <tr class="total-row">
            <td></td>
            <td>Total</td>
            <td class="text-right">{{ $data['totals']['sumwithvat'] }}€</td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="invoice-text-center">
    <p class="margin-bottom">VAT {{ $data['invoice']['vat'] }}% as per Estonian Value-added Tax Act §15 section 4 p 1.</p>
    <p><b>On payment order please refer to "Invoice {{ $data['invoice']['number'] }}".</b></p>
    <p>In case the invoice number is missing, we might not be able to connect your payment to your order.</p>
    <p>By paying you agree with the terms and conditions of Wisor Group OÜ.</p>
</div>

<div class="invoice-footer">
    <div class="invoice-footer-left">
        <p>Wisor Group OÜ</p>
        <p>Address: Narva mnt 5, Tallinn 10117, Estonia</p>
        <p>
            By continuing with the payment, you agree with our terms and
            conditions.
        </p>
    </div>
    <div class="invoice-footer-right">
        <p>Reg no: 14818750</p>
        <p>VAT ID: EE102195839</p>
    </div>
</div>
</body>
</html>
