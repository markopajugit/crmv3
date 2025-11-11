<?php

namespace App\Http\Controllers;

use App\Models\OrderService;
use Illuminate\Http\Request;

class OrderServiceController extends Controller
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderService  $orderService
     * @return \Illuminate\Http\Response
     */
    public function show(OrderService $orderService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderService  $orderService
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderService $orderService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'orderService' => 'required|integer|exists:order_service,id'
        ]);
        
        // Only allow fillable fields to prevent mass assignment vulnerability
        $orderServiceData = $request->only([
            'service_id', 'order_id', 'cost', 'name', 'date_from', 'date_to', 'renewed'
        ]);
        
        $orderService = OrderService::findOrFail($request->orderService);
        $orderService->update($orderServiceData);
        
        return response()->json(['success' => true, 'message' => 'Order service updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderService  $orderService
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderService $orderService)
    {
        //
    }
}
