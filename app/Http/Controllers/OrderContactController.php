<?php

namespace App\Http\Controllers;

use App\Models\OrderContact;
use App\Models\Person;
use Illuminate\Http\Request;

class OrderContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newOrderContact(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'person_id' => 'nullable|integer|exists:persons,id',
            'createPerson' => 'nullable|boolean'
        ]);
        $orderContact = new OrderContact;
        $orderContact->name = $request->name;
        if($request->person_id != 0){
            $orderContact->person_id = $request->person_id;
        } else {
            if($request->createPerson){
                $person = new Person;
                $person->name = $request->name;
                $person->email = $request->email;
                $person->save();
                $orderContact->person_id = $person->id;
            }
        }
        $orderContact->email = $request->email;
        $orderContact->order_id = $id;
        $orderContact->save();
        
        return response()->json(['success' => true, 'message' => 'Order contact created successfully']);
    }

    public function deleteOrderContact($id){
        $orderContact = OrderContact::findOrFail($id);
        $orderContact->delete();
        
        return response()->json(['success' => true, 'message' => 'Order contact deleted successfully']);
    }

    public function updateOrderContact(Request $request, $id){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255'
        ]);
        
        $orderContact = OrderContact::findOrFail($id);
        $orderContact->name = $request->name;
        $orderContact->email = $request->email;
        $orderContact->save();
        
        return response()->json(['success' => true, 'message' => 'Order contact updated successfully']);
    }
}
