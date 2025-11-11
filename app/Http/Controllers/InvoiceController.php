<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Person;
use App\Models\Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::where('is_proforma', false)->latest()->paginate(10);
        $type = 'all';

        return view('invoices.index',compact('invoices', 'type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function paidInvoices(){
        $invoices = Invoice::where('is_proforma', false)->whereNotNull('payment_date')->latest()->paginate(10);
        $type = 'paid';

        return view('invoices.index',compact('invoices', 'type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function unpaidInvoices(){
        $invoices = Invoice::where('is_proforma', false)->whereNull('payment_date')->latest()->paginate(10);
        $type = 'unpaid';

        return view('invoices.index',compact('invoices', 'type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'issue_date' => 'required|date',
            'payment_date' => 'required|date',
        ]);


        if($request->is_proforma){
            $today = date('Ymd');
            $count = Invoice::where('number', 'LIKE', $today . '%')->count();

            $nextInvoiceNo = '000';
            if($count > 0){
                $next = $count+1;
                $nextInvoiceNo = sprintf("%03d", $next);
            }

            $newArray = array_merge($request->all(), array('number' => date('Ymd').$nextInvoiceNo));

            $invoice = Invoice::create($newArray);

            $this->savePDF($invoice->id, $request->order_id, $request->all());
        } else {

            $currentOrder = Order::find($request->order_id);
            $invoiceNo = $currentOrder->number;
            $InvoiceData = array_merge($request->all(), array('number' => $invoiceNo));
            //dd($request);
            $invoice = Invoice::create($InvoiceData);

            $this->savePDF($invoice->id, $request->order_id, $InvoiceData);
        }

        return redirect()->route('invoices.index')
            ->with('success','Invoice created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $order = Order::where('id', $invoice->order_id)->first();
        $settings = Settings::all()->keyBy('key')->first();
        $company = Company::where('id', $order->company_id)->first();
        //dd($order);

        $servicesModel = $order->services;

        $services = array();

        foreach($servicesModel as $service){
            $services[] = $service;
        }


        $totals = array('sum' => 0);

        foreach ($services as $service){
            $totals['sum'] += $service['cost'];
        }

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 20){
            $totals['sumwithvat'] = $totals['sum']*1.2;
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        }

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];


        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals
        );

        return view('invoices.show',[
                'invoice' => $invoice,
                'order' => $order,
                'company' => $company,
                'services' => $services,
                'totals' => $totals,
                'data' => $data
            ]
        );
    }

    public function showProforma($id)
    {

        $invoice = Invoice::where('id', $id)->first();
        $order = Order::where('id', $invoice->order_id)->first();
        $settings = Settings::all()->keyBy('key')->first();
        $company = Company::where('id', $order->company_id)->first();
        //$orderModel = Order::where('id', $invoice->order_id);

        $servicesModel = $order->services;

        $services = array();

        foreach($servicesModel as $service){
            $services[] = $service;
        }


        $totals = array('sum' => 0);

        foreach ($services as $service){
            $totals['sum'] += $service['cost'];
        }

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 20){
            $totals['sumwithvat'] = $totals['sum']*1.2;
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        }

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];


        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals
        );

        return view('invoices.show',[
                'invoice' => $invoice,
                'order' => $order,
                'company' => $company,
                'services' => $services,
                'totals' => $totals,
                'data' => $data
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        //dd($invoice);

        if(!$invoice->is_proforma){
            $invoiceNo = Settings::where('key', 'Next_Invoice_no')->first();
            $invoiceNo->value = $invoiceNo->value - 1;
            $invoiceNo->save();
        }

        $file = File::where(['order_id' => $invoice->order_id])->first();

        $invoice->delete();
        if($file){
            $file->delete();

            Storage::delete($file->path);
        }

    }

    // Generate and download PDF from Invoice
    public function createPDF($id) {

        return response()->download(storage_path('app/public/files/invoice-'.$id.'.pdf'));

        $invoice = Invoice::findOrFail($id)->toArray();
        $order = Order::findOrFail($invoice['order_id'])->toArray();
        $settings = Settings::all()->keyBy('key')->toArray();
        $company = Company::findOrFail($order['company_id'])->toArray();

        $orderModel = Order::findOrFail($invoice['order_id']);

        $servicesModel = $orderModel->services;



        $services = array();

        foreach($servicesModel as $service){
            $services[] = $service->toArray();
        }


        $totals = array('sum' => 0);

        foreach ($services as $service){
            $totals['sum'] += $service['cost'];
        }

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        }

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];


        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals
        );

        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.wisorgroup', $data);
        return $pdf->download('invoice_'.$id.'.pdf');
    }

    // Generate PDF from Invoice
    public function viewPDF($id) {

        $invoice = Invoice::find($id);
        return response()->file(storage_path('app/public/files/invoice-'.$invoice->number.'.pdf'));

        $invoice = Invoice::findOrFail($id)->toArray();
        $order = Order::findOrFail($invoice['order_id'])->toArray();
        $settings = Settings::all()->keyBy('key')->toArray();
        $company = Company::findOrFail($order['company_id'])->toArray();

        $orderModel = Order::findOrFail($invoice['order_id']);

        $servicesModel = $orderModel->services;

        //dd($servicesModel);



        $services = array();

        foreach($servicesModel as $service){
            $services[] = $service->toArray();
        }


        $totals = array('sum' => 0);

        foreach ($services as $service){
            $totals['sum'] += $service['cost'];
        }

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        }

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];


        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals
        );

        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.wisorgroup', $data);
        return $pdf->stream();
    }

    private function savePDF($id, $orderId, $invoiceData = array()){
        //dd($invoiceData);
        $invoice = Invoice::findOrFail($id)->toArray();
        $order = Order::findOrFail($invoice['order_id'])->toArray();
        $settings = Settings::all()->keyBy('key')->toArray();

        if(isset($order['company_id'])){
            $company = Company::findOrFail($order['company_id'])->toArray();
        }

        if(isset($order['person_id'])){
            $company = Person::findOrFail($order['person_id'])->toArray();
        }

        $orderModel = Order::findOrFail($invoice['order_id']);

        $servicesModel = $orderModel->services;

        //dd($servicesModel);

        $services = array();

        foreach($servicesModel as $service){
            $services[] = array_merge($service->toArray());
        }



        $totals = array('sum' => 0);

        foreach ($services as $service){
            if(isset($service['pivot']) && isset($service['pivot']['cost'])){
                $totals['sum'] += $service['pivot']['cost'];
            } else {
                $totals['sum'] += $service['cost'];
            }
        }

        //dd($services);

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        }

        $invoice['issue_date'] = date('d.m.Y', strtotime($invoice['issue_date']));
        $invoice['payment_date'] = date('d.m.Y', strtotime($invoice['payment_date']));

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];

        $totals['sumwithvat'] = sprintf("%01.2f", $totals['sumwithvat']);
        $totals['vat'] = sprintf("%01.2f", $totals['vat']);
        $totals['sum'] = sprintf("%01.2f", $totals['sum']);

        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals,
            'invoiceData' => $invoiceData
        );

        view()->share('data',$data);

        if($invoiceData['invoicecompany'] == 'corptailor'){
            //dd($data);
            $pdf = PDF::loadView('pdf.special', $data);
        }
        else if($invoice['is_proforma']){
            $pdf = PDF::loadView('pdf.wisor-offer', $data);
        } else {
            $pdf = PDF::loadView('pdf.wisorgroup', $data);
        }

        $content = $pdf->download()->getOriginalContent();

        $file = new File;
        $invoiceNumber = $invoice['number'];
        if($invoice['is_proforma']){
            $file->name = 'priceoffer-'.$invoiceNumber.'.pdf';
        }else {
            $file->name = 'invoice-'.$invoiceNumber.'.pdf';
        }

        $file->path = 'public/files/'.$file->name;
        $file->order_id = $orderId;
        $file->save();

        Storage::put('public/files/'.$file->name,$content) ;
    }

    // Generate PDF from Invoice
    public function testPDF($id) {

        $invoice = Invoice::findOrFail($id)->toArray();
        $order = Order::findOrFail($invoice['order_id'])->toArray();
        $settings = Settings::all()->keyBy('key')->toArray();
        $company = Company::findOrFail($order['company_id'])->toArray();

        $orderModel = Order::findOrFail($invoice['order_id']);

        $servicesModel = $orderModel->services;



        $services = array();

        foreach($servicesModel as $service){
            $services[] = $service->toArray();
        }


        $totals = array('sum' => 0);

        foreach ($services as $service){
            $totals['sum'] += $service['cost'];
        }

        if($invoice['vat'] == 22){
            $totals['sumwithvat'] = $totals['sum']*1.22;
        } else if($invoice['vat'] == 0){
            $totals['sumwithvat'] = $totals['sum'];
        } else if($invoice['vat'] == 24){
            $totals['sumwithvat'] = $totals['sum']*1.24;
        }

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];


        $data = array(
            'invoice' => $invoice,
            'settings' => $settings,
            'order' => $order,
            'company' => $company,
            'services' => $services,
            'totals' => $totals
        );

        //dd($data);

        //return view('pdf.wisergroup');

        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.wisor-offer', $data);
        return $pdf->stream();

    }

    public function proformas(){
        $proformas = Invoice::where('is_proforma', true)->latest()->paginate(10);

        return view('proformas.index',compact('proformas'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function searchByPayer($payerName){
        $invoices = Invoice::where('payer_name', 'like', '%'.$payerName.'%')->latest()->paginate(10);
        $type = 'payer - '. $payerName;

        return view('invoices.index',compact('invoices', 'type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

}
