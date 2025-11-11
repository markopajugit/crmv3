<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::all();

        return view('settings.index',compact('settings'));
    }

    public function store(Request $request)
    {
        // Loop through the request data
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            // Update the setting in the database
            //dd($key, $value);
            Settings::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('settings.index')->with('success', 'Items updated successfully.');
    }
}
