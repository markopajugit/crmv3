<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EntityAddress;
use App\Models\Person;
use Illuminate\Http\Request;

class EntityAddressController extends Controller
{
    public function deleteEntityAddress($contactId){
        $entityContact = EntityAddress::find($contactId);
        $entityContact->delete();
    }

    public function addNewEntityAddress(Request $request, $entityId){
        $entityAddress = new EntityAddress();
        if($request->entity == 'person'){
            $entityAddress->person_id = $entityId;
        } elseif($request->entity == 'company'){
            $entityAddress->company_id = $entityId;
        }
        $entityAddress->street = $request->street;
        $entityAddress->city = $request->city;
        $entityAddress->zip = $request->zip;
        $entityAddress->country = $request->country;
        $entityAddress->note = $request->address_note;
        $entityAddress->save();
    }

    public function updateEntityAddress(Request $request, $contactId){
        if($contactId == 0){
            if($request->entity == 'person'){
                $person = Person::find($request->person_id);
                $person->address_street = $request->street;
                $person->address_city = $request->city;
                $person->address_zip = $request->zip;
                $person->address_dropdown = $request->country;
                $person->address_note = $request->address_note;
                $person->save();
            } elseif($request->entity == 'company'){
                $company = Company::find($request->company_id);
                $company->address_street = $request->street;
                $company->address_city = $request->city;
                $company->address_zip = $request->zip;
                $company->address_dropdown = $request->country;
                $company->address_note = $request->address_note;
                $company->save();
            }
        } else {
            $entityAddress = EntityAddress::find($contactId);
            $entityAddress->street = $request->street;
            $entityAddress->city = $request->city;
            $entityAddress->zip = $request->zip;
            $entityAddress->country = $request->country;
            $entityAddress->note = $request->address_note;
            $entityAddress->save();
        }
    }
}
