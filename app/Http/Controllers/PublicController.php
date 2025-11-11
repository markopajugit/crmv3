<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function publicClientEdit($id){
        return view('public.index');
    }

    public function publicClientSave($id, Request $request){

    }
}
