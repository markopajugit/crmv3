<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EntityAddress;
use App\Models\Person;
use Illuminate\Http\Request;

class EntityAddressController extends Controller
{
    public function deleteEntityContact($contactId){
        dd($contactId);
        $entityContact = EntityAddress::find($contactId);
        $entityContact->delete();
    }
}
