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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
    public function index(Request $request)
    {
        $query = Invoice::where('is_proforma', false)->with(['order.company', 'order.person']);

        // Text search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('number', 'LIKE', '%' . $search . '%')
                  ->orWhere('payer_name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('company', function($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhereHas('person', function($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                  });
            });
        }

        // Type filter (all, paid, unpaid)
        $type = $request->get('type', 'all');
        if ($type === 'paid') {
            $query->whereNotNull('payment_date');
        } elseif ($type === 'unpaid') {
            $query->whereNull('payment_date');
        }

        $invoices = $query->latest()->paginate(10)->appends(request()->query());

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('invoices.partials.table', compact('invoices'))->render(),
                'pagination' => view('invoices.partials.pagination', compact('invoices'))->render(),
                'total' => $invoices->total()
            ]);
        }

        return view('invoices.index', compact('invoices', 'type'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function paidInvoices(Request $request){
        $request->merge(['type' => 'paid']);
        return $this->index($request);
    }

    public function unpaidInvoices(Request $request){
        $request->merge(['type' => 'unpaid']);
        return $this->index($request);
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
        try {
            $validatedData = $request->validate([
                'issue_date' => 'required|date',
                'payment_date' => 'required|date',
            ]);


            if($request->is_proforma){
                $count = Invoice::whereRaw("number LIKE CONCAT(DATE_FORMAT(NOW(), '%Y%m%d'), '%')")->count();

                $nextInvoiceNo = '000';
                if($count > 0){
                    $next = $count+1;
                    $nextInvoiceNo = sprintf("%03d", $next);
                }

                $newArray = array_merge($request->all(), array('number' => date('Ymd').$nextInvoiceNo));

                $invoice = Invoice::create($newArray);

                // Generate PDF asynchronously to avoid blocking the response
                $this->savePDFAsync($invoice->id, $request->order_id, $request->all());
            } else {

                $currentOrder = Order::find($request->order_id);
                if(!$currentOrder){
                    throw new \Exception('Order not found');
                }
                $invoiceNo = $currentOrder->number;
                $InvoiceData = array_merge($request->all(), array('number' => $invoiceNo));
                //dd($request);
                $invoice = Invoice::create($InvoiceData);

                // Generate PDF asynchronously to avoid blocking the response
                $this->savePDFAsync($invoice->id, $request->order_id, $InvoiceData);
            }

            // If AJAX request, return JSON response immediately (PDF generation happens in background)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => []  // Empty error object indicates success (matches frontend expectation)
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success','Invoice created successfully.');

        } catch (\Exception $e) {
            // If AJAX request, return JSON error response
            if ($request->ajax() || $request->wantsJson()) {
                Log::error('Invoice creation error: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'error' => ['message' => $e->getMessage()]
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
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
        // Optimize: Fetch order once with relationships
        $orderModel = Order::with('services')->findOrFail($orderId);
        
        if(!$orderModel){
            throw new \Exception('Order not found');
        }

        $invoice = Invoice::findOrFail($id);
        $invoiceArray = $invoice->toArray();
        
        // Cache settings to avoid repeated queries
        $settings = Cache::remember('settings_all', 3600, function() {
            return Settings::all()->keyBy('key')->toArray();
        });

        $company = null;
        if($orderModel->company_id){
            $company = Company::findOrFail($orderModel->company_id)->toArray();
        } elseif($orderModel->person_id){
            $company = Person::findOrFail($orderModel->person_id)->toArray();
        }

        if($company === null){
            throw new \Exception('Order must have either a company_id or person_id');
        }

        $servicesModel = $orderModel->services;

        //dd($servicesModel);

        $services = [];
        $totals = ['sum' => 0];

        // Optimize: Calculate totals in single loop
        foreach($servicesModel as $service){
            $serviceArray = $service->toArray();
            $services[] = $serviceArray;
            
            // Get cost from pivot if available, otherwise from service
            $cost = $service->pivot->cost ?? $service->cost ?? 0;
            $totals['sum'] += (float) $cost;
        }

        // Calculate VAT totals
        $vatRate = (float) ($invoiceArray['vat'] ?? 0);
        if($vatRate == 22){
            $totals['sumwithvat'] = $totals['sum'] * 1.22;
        } else if($vatRate == 20){
            $totals['sumwithvat'] = $totals['sum'] * 1.2;
        } else if($vatRate == 24){
            $totals['sumwithvat'] = $totals['sum'] * 1.24;
        } else {
            $totals['sumwithvat'] = $totals['sum'];
        }

        // Format dates
        $invoiceArray['issue_date'] = date('d.m.Y', strtotime($invoiceArray['issue_date']));
        $invoiceArray['payment_date'] = date('d.m.Y', strtotime($invoiceArray['payment_date']));

        $totals['vat'] = $totals['sumwithvat'] - $totals['sum'];

        // Format totals
        $totals['sumwithvat'] = sprintf("%01.2f", $totals['sumwithvat']);
        $totals['vat'] = sprintf("%01.2f", $totals['vat']);
        $totals['sum'] = sprintf("%01.2f", $totals['sum']);

        $data = array(
            'invoice' => $invoiceArray,
            'settings' => $settings,
            'order' => $orderModel->toArray(),
            'company' => $company,
            'services' => $services,
            'totals' => $totals,
            'invoiceData' => $invoiceData
        );

        view()->share('data',$data);

        // Determine PDF template
        $template = 'pdf.wisorgroup';
        if(isset($invoiceData['invoicecompany']) && $invoiceData['invoicecompany'] == 'corptailor'){
            $template = 'pdf.special';
        } else if($invoiceArray['is_proforma']){
            $template = 'pdf.wisor-offer';
        }

        // Generate PDF
        $pdf = PDF::loadView($template, $data);
        $content = $pdf->output();

        // Save file record
        $invoiceNumber = $invoiceArray['number'];
        $fileName = ($invoiceArray['is_proforma'] ? 'priceoffer-' : 'invoice-') . $invoiceNumber . '.pdf';
        $filePath = 'public/files/' . $fileName;

        $file = new File;
        $file->name = $fileName;
        $file->path = $filePath;
        $file->order_id = $orderId;
        $file->save();

        // Store PDF file
        Storage::put($filePath, $content);
    }

    /**
     * Generate PDF asynchronously using dispatch to avoid blocking the request
     */
    private function savePDFAsync($id, $orderId, $invoiceData = array()){
        // Use dispatch to run PDF generation in background
        // This prevents blocking the HTTP response
        dispatch(function() use ($id, $orderId, $invoiceData) {
            try {
                $this->savePDF($id, $orderId, $invoiceData);
            } catch (\Exception $e) {
                Log::error('PDF generation failed: ' . $e->getMessage(), [
                    'invoice_id' => $id,
                    'order_id' => $orderId,
                    'trace' => $e->getTraceAsString()
                ]);
            }
        })->afterResponse();
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
