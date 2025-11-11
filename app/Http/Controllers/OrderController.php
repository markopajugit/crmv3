<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyOrder;
use App\Models\Order;
use App\Models\OrderContact;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Settings;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        $query = Order::with(['company', 'person', 'responsible_user', 'orderServices']);

        // Text search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('number', 'LIKE', '%' . $search . '%')
                  ->orWhere('name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('person', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Dynamically filter based on user_id
        if ($request->filled('responsible') && $request->input('responsible') !== 'all') {
            $query->where('responsible_user_id', $request->input('responsible'));
        }

        // Dynamically filter based on status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Dynamically filter based on payment_status
        if ($request->filled('payment_status') && $request->input('payment_status') !== 'all') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Retrieve the filtered results
        $orders = $query->latest()->paginate(30)->appends(request()->query());

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('orders.partials.table', compact('orders'))->render(),
                'pagination' => view('orders.partials.pagination', compact('orders'))->render(),
                'total' => $orders->total()
            ]);
        }

        return view('orders.index', compact('orders', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $services = Service::all();
        $companies = Company::all();
        return view('orders.create',[
                'services' => $services,
                'companies' => $companies
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $orderNo = Settings::where('key', 'next_order_no')->first();
        $orderNumber = 'C'.date('y').sprintf('%04u', $orderNo->value);
        $orderNo->value = $orderNo->value + 1;
        $orderNo->save();

        Order::create(array_merge($request->all(), array('number' => $orderNumber)));

        return redirect()->route('orders.index')
            ->with('success','Order created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRelatedCompany($company_id, $order_id)
    {
        $data = array('company_id' => $company_id, 'order_id' => $order_id);

        CompanyOrder::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show',[
                'order' => $order,
                'services' => Service::all(),
                'service_categories' => ServiceCategory::all()
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $services = Service::all();

        foreach($services as $key => $service){
            foreach($order->services as $orderService){
                if($orderService->id == $service->id){
                    $services[$key]->checked = true;
                }
            }
        }

        return view('orders.edit',[
                'order' => $order,
                'services' => $services
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if(isset($request->responsible_user_id) && auth()->user()->id != $request->responsible_user_id){
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From: crm@wisorgroup.com";
            $user = Auth::user();
            $responsibleUser = User::find($request->responsible_user_id);
            $responsibleUserEmail = $responsibleUser->email;
            $company = Company::find($order->company_id);
            $message = "Order: ".$company->name." <a href='https://crm.wisorgroup.com/orders/".$order->id."'>".$order->id."</a><br>
            Payment: ".$request->payment_status."<br>
            Changed by: ".$user->name."<br>";

            mail($responsibleUserEmail,"Order ".$company->name." is now ".$request->payment_status,$message,$headers);
        }

        $order->update(array_merge($request->all(), ['notification_sent' => false]));

        //dd($request->services);
        if($request->services){
            $order->services()->sync($request->services);
        }

        return view('orders.show',[
                'order' => $order,
                'services' => Service::all(),
                'service_categories' => ServiceCategory::all()
            ]
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $orderServices = OrderService::where('order_id', $id)->get();

        foreach($orderServices as $orderService){
            $orderService->delete();
        }

        $order = Order::find($id);
        $order->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRelatedService(Request $request)
    {
        foreach($request->service_id as $service_id){
            $service = Service::find($service_id);
            if($service->type == 'Reaccuring'){
                $service->date_from = date('d.m.Y');
                if($service->reaccuring_frequency == 3){
                   $service->date_to = date('d.m.Y', strtotime('+3 months -1 days'));
                } elseif($service->reaccuring_frequency == 6){
                    $service->date_to = date('d.m.Y', strtotime('+6 months -1 days'));
                } elseif($service->reaccuring_frequency == 12){
                    $service->date_to = date('d.m.Y', strtotime('+1 year -1 days'));
                }
            }

            OrderService::create(['order_id'=>$request->order_id,'service_id' => $service_id,'name' => $service->name, 'cost' => $service->cost, 'date_from' => $service->date_from, 'date_to' => $service->date_to]);
        }


        return redirect()->route('orders.index')
            ->with('success','Service added successfully.');
    }

    public function storeOrderPayment($orderId, Request $request){
        //dd($orderId, $request);
        $payment = new Payment;
        $payment->order_id = $orderId;
        $payment->type = $request->type;
        $payment->sum = $request->sum;
        $payment->details = $request->details;
        $payment->paid_date = $request->paid_date;

        $payment->save();
    }


    public function updateRelatedService(Request $request)
    {

    }

    public function deleteOrderPayment($paymentId){
        $payment = Payment::find($paymentId);
        $payment->delete();
    }

    public function updateOrderPayment(Request $request, $paymentId){
        $payment = Payment::find($paymentId);
        $payment->type = $request->type;
        $payment->sum = $request->sum;
        $payment->details = $request->details;
        $payment->paid_date = $request->paid_date;
        $payment->save();
    }

    public function deleteRelatedService(Request $request)
    {
        $relation = OrderService::where([
            ['id', '=', $request->orderserviceid]
        ]);

        $relation->delete();

        return redirect()->route('orders.index')
            ->with('success','Related Service removed');
    }

    public function renewals(Request $request)
    {
        // Auto-renewal logic (only run if not AJAX request)
        if (!$request->ajax() && !$request->has('ajax')) {
            $orders = Order::with('services')->get();
            $ordersArray = array();

            foreach($orders as $order){
                if(count($order->services) != 0){
                    foreach($order->services as $orderService){
                        if($orderService->pivot->date_from && $orderService->pivot->date_to){

                            if($orderService->type == 'Reaccuring' && $orderService->reaccuring_frequency == '3' && date('Y.m.d', strtotime($orderService->pivot->date_to)) < date('Y.m.d', strtotime('+15 days'))){
                                $newDateFrom = ' + 3 months';
                                $newDateTo = ' + 3 months';
                                $createRenewal = true;
                            } else if($orderService->type == 'Reaccuring' && $orderService->reaccuring_frequency == '6' && date('Y.m.d', strtotime($orderService->pivot->date_to)) < date('Y.m.d', strtotime('+1 month'))){
                                $newDateFrom = ' + 6 months';
                                $newDateTo = ' + 6 months';
                                $createRenewal = true;
                            } else if($orderService->type == 'Reaccuring' && $orderService->reaccuring_frequency == '12' && date('Y.m.d', strtotime($orderService->pivot->date_to)) < date('Y.m.d', strtotime('+2 months'))){
                                $newDateFrom = ' + 1 years';
                                $newDateTo = ' + 1 years';
                                $createRenewal = true;
                            } else {
                                $createRenewal = false;
                            }

                            if($createRenewal){
                                $orderServiceCheck = OrderService::find($orderService->pivot->id);
                                if($order->id == 5850 && isset($_GET['debug'])){
                                    //$test = $order->replicate();
                                    //dd($test->payments()->get());
                                }
                                if($orderServiceCheck->renewed == 1){
                                    continue;
                                }

                                $newOrder = $order->replicate();

                                unset($newOrder->id);
                                unset($newOrder->notes);
                                unset($newOrder->invoices);
                                unset($newOrder->files);
                                $orderNo = Settings::where('key', 'next_order_no')->first();
                                $orderNumber = 'C'.date('y').sprintf('%04u', $orderNo->value);
                                $orderNo->value = $orderNo->value + 1;
                                $orderNo->save();

                                $newOrder->number = $orderNumber;
                                $newOrder->status = "Not Active";
                                $newOrder->payment_status = "Not paid";
                                $newOrder->paid_date = null;
                                $newOrder->renewed_from_order_id = $order->id;

                                $newOrder->save();
                                $ordersArray[] = $newOrder;

                                foreach($order->services as $orderServiceCheckForEndDateDuplicates){
                                    if($orderService->pivot->date_to == $orderServiceCheckForEndDateDuplicates->pivot->date_to && $orderService->pivot->id != $orderServiceCheckForEndDateDuplicates->pivot->id){
                                        $currentOrderService = OrderService::find($orderServiceCheckForEndDateDuplicates->pivot->id);
                                        $currentOrderService->renewed = 1;
                                        $currentOrderService->save();

                                        $replicatedService = new OrderService();
                                        $replicatedService->service_id = $orderServiceCheckForEndDateDuplicates->pivot->service_id;
                                        $replicatedService->order_id = $newOrder->id;
                                        $replicatedService->name = $orderServiceCheckForEndDateDuplicates->name;
                                        $replicatedService->cost = $orderServiceCheckForEndDateDuplicates->pivot->cost;
                                        $replicatedService->date_from = date('d.m.Y', strtotime($orderServiceCheckForEndDateDuplicates->pivot->date_from. $newDateFrom));
                                        $replicatedService->date_to = date('d.m.Y', strtotime($orderServiceCheckForEndDateDuplicates->pivot->date_to. $newDateTo));
                                        $replicatedService->save();
                                    }
                                }

                                $currentOrderService = OrderService::find($orderService->pivot->id);
                                $currentOrderService->renewed = 1;
                                $currentOrderService->save();

                                $replicatedService = new OrderService();
                                $replicatedService->service_id = $orderService->pivot->service_id;
                                $replicatedService->order_id = $newOrder->id;
                                $replicatedService->name = $orderService->name;
                                $replicatedService->cost = $orderService->pivot->cost;
                                $replicatedService->date_from = date('d.m.Y', strtotime($orderService->pivot->date_from. $newDateFrom));
                                $replicatedService->date_to = date('d.m.Y', strtotime($orderService->pivot->date_to. $newDateTo));
                                $replicatedService->save();


                                foreach($order->getOrderContacts as $orderContact){
                                    $newOrderContact = new OrderContact();
                                    $newOrderContact->order_id = $newOrder->id;
                                    $newOrderContact->name = $orderContact->name;
                                    $newOrderContact->email = $orderContact->email;
                                    $newOrderContact->person_id = $orderContact->person_id;
                                    $newOrderContact->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        // Query for renewed orders
        $query = Order::whereNotNull('renewed_from_order_id')
            ->where('payment_status', '<>', 'Paid')
            ->where('status', '<>', 'Finished')
            ->with(['company', 'person', 'responsible_user']);

        // Text search
        $search = $request->get('search', '');
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('number', 'LIKE', '%' . $search . '%')
                  ->orWhere('name', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('person', function($q) use ($search) {
                      $q->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        $renewedOrders = $query->latest()->paginate(10)->appends(request()->query());

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'html' => view('renewals.partials.table', compact('renewedOrders'))->render(),
                'pagination' => view('renewals.partials.pagination', compact('renewedOrders'))->render(),
                'total' => $renewedOrders->total()
            ]);
        }

        return view('renewals.index', compact('renewedOrders'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function filter(){
        dd("ORDERS FILTER");
    }
}
