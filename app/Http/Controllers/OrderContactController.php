<?php

namespace App\Http\Controllers;

use App\Models\OrderContact;
use App\Models\Person;
use Illuminate\Http\Request;

class OrderContactController extends Controller
{
    public function newOrderContact(Request $request, $id){
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
    }

    public function deleteOrderContact($id){
        $orderContact = OrderContact::find($id);
        $orderContact->delete();
    }

    public function updateOrderContact(Request $request, $id){
        $orderContact = OrderContact::find($id);
        $orderContact->name = $request->name;
        $orderContact->email = $request->email;
        $orderContact->save();
    }
}
