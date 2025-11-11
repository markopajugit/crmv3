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

        <div style="display: inline-block;width: 60%; font-size: 12px;">
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
            </table>
        </div>

        <div class="logo" style="display: inline-block; width: 39%;">
            <img src="{{ asset('images/logoInvoice.png') }}" style="width: 150px; height: 150px;">
        </div>


        <div style="font-size: 12px;">

            <div style="width:60%; display: inline-block; border-bottom: 1px solid black; margin-bottom:10px;">
                <div style="width:25%; display: inline-block; padding-bottom:2px;">Contacts:</div>
                <div style="width:69%; display: inline-block;">office@alt-accouting.com | +370 600 26 147</div>
            </div>
            <div style="width:39%; display: inline-block; background: lightgray; padding-top: 5px; padding-left:3px; border-bottom: 1px solid black; margin-left:-4px;">
                <div style="width:50%; display: inline-block;  font-size:16px; font-weight: bold;">PRO FORMA #</div>
                <div style="width:30%; display: inline-block;  font-size:16px; font-weight: bold;">O{{ now()->year }}-{{ $data['invoice']['order_id'] }}</div>
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

                <tr>
                    <td>Client:</td>
                    <td class="bold">{{ $data['company']['name'] }}</td>
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
                        <td>{{ $service['name'] }}</td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: center;">{{ $service['cost'] }}</td>
                        <td style="text-align: center;">{{ $service['cost'] }}.00</td>
                        <td style="text-align: center;">EUR</td>
                    </tr>
                @endforeach
            </table>
        </div>


        <div style="margin: 30px 0; font-size: 16px; margin-left: 50%">
            <table style="width:100%;">
                <tr>
                    <td>Total sum:</td>
                    <td class="bold">{{ $data['totals']['sum'] }}</td>
                    <td class="bold">EUR</td>
                </tr>
                <tr>
                    <td>VAT({{ $data['invoice']['vat'] }}%):</td>
                    <td class="bold">{{ $data['totals']['vat'] }}</td>
                    <td class="bold">EUR</td>
                </tr>

                <tr>
                    <td class="bold bg">TOTAL AMOUNT DUE:</td>
                    <td class="bold bg">{{ $data['totals']['sumwithvat'] }}</td>
                    <td class="bold bg">EUR</td>
                </tr>
            </table>
        </div>

        <hr style="margin-top: 50px; margin-bottom: 30px;">

        <div style="font-size: 12px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width:50%;">Payable to:</td>
                    <td style="width:50%;" class="bold">ALT corporate UAB</td>
                </tr>
                <tr>
                    <td>IBAN:</td>
                    <td class="bold">LT164010051005584204</td>
                </tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                    <td>AMOUNT DUE</td>
                    <td class="bold">{{ $data['totals']['sumwithvat'] }} EUR</td>
                </tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                    <td class="bold">Bank</td>
                    <td>Luminor Bank AS Lietuvos Skyrius</td>
                </tr>
                <tr>
                    <td class="bold">BANK ADDRESS: </td>
                    <td>Konstitucijos 21A, 03601 Vilnius, Lietuva
                    </td>
                </tr>
                <tr>
                    <td class="bold">SWIFT/BIC:</td>
                    <td>AGBLLT2X
                    </td>
                </tr>
            </table>
        </div>
@endsection
