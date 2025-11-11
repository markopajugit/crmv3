@extends('layouts.app')

@section('content')
    <div id="invoiceHTML" style="min-width:500px; max-width:800px; padding:20px; background:white; font-family: 'Open Sans',sans-serif;">
        <style>
            td.underline, th.underline {
                text-decoration: underline;
            }


            td.bold, th.bold {
                font-weight: bold;
            }


            td.bg {
                background: lightgray;
            }


            .arveread th{
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }

            .arveread {
                border-bottom: 1px solid black;
            }



        </style>

        <div style="display: inline-block;width: 60%; font-size: 12px;font-family: 'Open Sans', sans-serif;">
            <table>
                <tr>
                    <td style="width:30%">Service provider:</td>
                    <td style="font-weight: bold;">ALT corporate UAB</td>
                </tr>
                <tr>
                    <td>Reg. no: </td>
                    <td style="font-weight: bold;">306001350</td>
                </tr>
                <tr>
                    <td>VAT no:</td>
                    <td style="font-weight: bold;">LT100014877019</td>
                </tr>
                <tr>
                    <td>Address: </td>
                    <td>Vilnius, Vilniaus str. 25, Vilnius 01402,
                        Lithuania
                    </td>
                </tr>
                <tr>
                    <td>Contacts: </td>
                    <td>office@alt-accouting.com | +370 600 26 147
                    </td>
                </tr>
            </table>
        </div>

        <div class="logo" style="display: inline-block; width: 39%;">
            <img src="{{ asset('images/logo.svg') }}" style="width: 150px; height: 150px;">
        </div>


        <div style="font-size: 12px;">

            <div style="width:60%; display: inline-block; border-bottom: 1px solid black;">
                <!--<div style="width:25%; display: inline-block;">Contacts:</div>
                <div style="width:69%; display: inline-block;">office@alt-accouting.com | +370 600 26 147</div>-->
            </div>
            <div style="width:39%; display: inline-block; background: lightgray; padding-top: 5px; padding-left:3px; border-bottom: 1px solid black; margin-left:-4px;">
                <div style="width:50%; display: inline-block;  font-size:16px; font-weight: bold;">@if(isset($invoiceData['is_proforma']) && $invoiceData['is_proforma'] != 0) PRO FORMA # @else INVOICE # @endif</div>
                <div style="width:35%; display: inline-block;  font-size:16px; font-weight: bold;">O{{ now()->year }}-{{ $data['invoice']['order_id'] }}</div>
            </div>

            <table>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Date:</td>
                    <td class="bold">{{ $data['invoice']['issue_date'] }}</td>
                </tr>

                <?php
                if(isset($invoiceData['name'])){
                    $data['company']['name'] = $invoiceData['name'];
                } else {
                    $data['company']['name'] = '';
                }

                if(isset($invoiceData['registry_code'])){
                    $data['company']['registry_code'] = $invoiceData['registry_code'];
                }

                if(isset($invoiceData['address'])){
                    $data['company']['address'] = $invoiceData['address'];
                } else {
                    $data['company']['address'] = '';
                }

                if(!isset($data['company']['registry_code'])){
                    $data['company']['registry_code'] = 'N/A';
                }

                if(!isset($data['company']['vat'])){
                    $data['company']['vat'] = 'N/A';
                }
                ?>
                <tr>
                    <td>Client:</td>
                    <td class="bold">{{ $data['company']['name'] }} (reg.code:{{ $data['company']['registry_code'] }})</td>
                    <td style="width:100px;"></td>
                    <td>Payment date:</td>
                    <td class="bold">{{ $data['invoice']['payment_date'] }}</td>
                </tr>

                <tr>
                    <td>VAT no:</td>
                    <td class="bold">{{ $data['company']['vat'] }}</td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Address:</td>
                    <td style="width:210px;">{{ $data['company']['address'] }}</td>
                    <!--<td style="min-width:210px;">asdasd</td>-->
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>


            <table class="arveread" style="margin-top: 30px;width:100%; border-spacing:0;" >
                <tr>
                    <th class="bold" style="padding: 10px 5px;">Article</th>
                    <th class="bold">Service description</th>
                    <th class="bold">Amount</th>
                    <th class="bold">Price</th>
                    <th class="bold">Total</th>
                    <th class="bold">Currency</th>
                </tr>

                @foreach($services as $key => $service)
                    <tr>
                        <td style="text-align: center; padding: 5px 10px;">{{ ++$key }}</td>
                        <td>{{ $service['pivot']['name'] }} @if ($service['type'] == 'Reaccuring') (Reaccuring {{ $service['reaccuring_frequency'] }}mo) @endif</td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: center;">{{ $service['pivot']['cost'] }}</td>
                        <td style="text-align: center;">{{ $service['pivot']['cost'] }}.00</td>
                        <td style="text-align: center;">EUR</td>
                    </tr>
                @endforeach
            </table>
        </div>


        <div style="margin: 30px 0; font-size: 16px; margin-left: 50%">
            <table style="width:100%;">
                <tr>
                    <td>Total sum:</td>
                    <td class="bold">{{ $data['totals']['sum'] }}.00</td>
                    <td class="bold">EUR</td>
                </tr>
                <tr>
                    <td>VAT({{ $data['invoice']['vat'] }}%):</td>
                    <td class="bold">{{ $data['totals']['vat'] }}.00</td>
                    <td class="bold">EUR</td>
                </tr>

                @if($invoice['vat_comment'])
                    <tr>
                        <p style="font-size:12px;margin:0;">{{ $invoice['vat_comment'] }}</p>
                    </tr>
                @endif
                <tr>
                    <td class="bold bg">TOTAL AMOUNT DUE:</td>
                    <td class="bold bg">{{ $data['totals']['sumwithvat'] }}.00</td>
                    <td class="bold bg">EUR</td>
                </tr>
            </table>
        </div>

        <hr style="margin-top: 20px; margin-bottom: 15px;">

        <div style="font-size: 12px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width:30%;">Recipient:</td>
                    <td class="bold">ALT corporate UAB</td>
                </tr>
                <tr>
                    <td>Account:</td>
                    <td class="bold">LT164010051005584204</td>
                </tr>
                <tr>
                    <td>SWIFT/BIC:</td>
                    <td class="bold">AGBLLT2X</td>
                </tr>
                <tr>
                    <td>Bank:</td>
                    <td class="bold">Luminor Bank AS Lietuvos Skyrius</td>
                </tr>
                <tr>
                    <td>Bank address: </td>
                    <td class="bold">Konstitucijos 21A, 03601 Vilnius, Lietuva
                    </td>
                </tr>
            </table>
        </div>

        <hr style="margin-top: 20px; margin-bottom: 15px;">

        <div style="font-size: 12px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width:30%;">Recipient:</td>
                    <td class="bold">ALT Corporate UAB</td>
                </tr>
                <tr>
                    <td>Account:</td>
                    <td class="bold">LT80 3250 0247 8382 8623 </td>
                </tr>
                <tr>
                    <td>BIC for EUR:</td>
                    <td class="bold">REVOLT21</td>
                </tr>
                <tr>
                    <td>BIC for non-EUR:</td>
                    <td class="bold">CHASGB2L</td>
                </tr>
                <tr>
                    <td>Bank:</td>
                    <td class="bold">Revolut Bank UAB</td>
                </tr>
                <tr>
                    <td>Bank address:</td>
                    <td class="bold">Konstitucijos ave. 21B, 08130, Vilnius, LT</td>
                </tr>
            </table>
        </div>
@endsection
