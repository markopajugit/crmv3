<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Order;
use App\Models\Person;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('files.upload');
    }

    public function store(Request $request)
    {
        //dd($request);
        $name = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('public/files');

        $file = new File;
        $file->name = $name;
        $file->path = $path;
        $redirect = 'default';


        if($request->companyID){
            $file->company_id = $request->companyID;
            $redirect = 'company';
        } else if($request->personID){
            $file->person_id = $request->personID;
            $redirect = 'person';
        } else if($request->orderID){
            $file->order_id = $request->orderID;
            $redirect = 'order';
        }

        $file->save();
    }

    public function storeVirtualOfficeDocument(Request $request)
    {
        //dd($request);
        $name = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('public/files');

        $file = new File;
        $file->name = $name;
        $file->path = $path;
        $file->virtual_office = 1;
        $redirect = 'default';


        if($request->companyID){
            $file->company_id = $request->companyID;
            $redirect = 'company';
        } else if($request->personID){
            $file->person_id = $request->personID;
            $redirect = 'person';
        } else if($request->orderID){
            $file->order_id = $request->orderID;
            $redirect = 'order';
        }

        $file->save();
    }

    public function viewUploadedCompanyFile($companyId, $name){
        $user = auth()->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $file = File::where(['name' => $name, 'company_id' => $companyId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        $filePath = storage_path('/app/'.$file->path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($filePath);
    }

    public function viewUploadedPersonFile($personId, $name){
        $user = auth()->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $file = File::where(['name' => $name, 'person_id' => $personId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        $filePath = storage_path('/app/'.$file->path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($filePath);
    }

    public function viewUploadedOrderFile($orderId, $name){
        $user = auth()->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $file = File::where(['name' => $name, 'order_id' => $orderId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        $filePath = storage_path('/app/'.$file->path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        return response()->file($filePath);
    }

    public function downloadUploadedCompanyFile($companyId, $name){
        $user = auth()->user();
        if(!$user){
            die();
        }
        $file = File::where(['name' => $name, 'company_id' => $companyId])->first();
        return response()->download(storage_path('/app/'.$file->path), $name);
    }

    public function downloadUploadedPersonFile($personId, $name){
        $user = auth()->user();
        if(!$user){
            die();
        }
        $file = File::where(['name' => $name, 'person_id' => $personId])->first();
        return response()->download(storage_path('/app/'.$file->path), $name);
    }

    public function downloadUploadedOrderFile($orderId, $name){
        $user = auth()->user();
        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $file = File::where(['name' => $name, 'order_id' => $orderId])->first();
        
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }
        
        $filePath = storage_path('/app/'.$file->path);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File does not exist on disk'], 404);
        }
        
        $order = Order::find($orderId);
        $downloadName = $name;
        
        if($order && $order->company_id){
            $company = Company::find($order->company_id);
            $downloadName = $company->name.' invoice '.$order->number;
        }
        
        return response()->download($filePath, str_replace('.', '', $downloadName));
    }

    public function sendTestEmail(){
        var_dump(mail("ouhardcoded@gmail.com","Test","Test mail from crm"));
        echo "MAIL SENT";
    }

    public function deleteUploadedCompanyFile($companyId, $name){
        $user = auth()->user();
        if(!$user){
            die();
        }
        $file = File::where(['name' => $name, 'company_id' => $companyId])->first();
        Storage::delete($file->path);
        $file->delete();
    }

    public function deleteUploadedPersonFile($personId, $name){
        $user = auth()->user();
        if(!$user){
            die();
        }
        $file = File::where(['name' => $name, 'person_id' => $personId])->first();
        Storage::delete($file->path);
        $file->delete();
    }

    public function deleteUploadedOrderFile($orderId, $name){
        $user = auth()->user();
        if(!$user){
            die();
        }
        $file = File::where(['name' => $name, 'order_id' => $orderId])->first();
        Storage::delete($file->path);
        $file->delete();
    }


    public function updateArchiveNumber(Request $request, $fileId){
        $file = File::find($fileId);
        $file->archive_nr = $request->archive_nr;
        $file->save();

    }

    public function showDocuments(Request $request){
        $files = File::all();
        $categorizedFiles = array('virtualoffice' => [], 'general' => [], 'archived' => []);
        foreach($files as $file){
            if($file->archive_nr){
                $categorizedFiles['archived'][] = $file;
            } elseif($file->virtual_office){
                $categorizedFiles['virtualoffice'][] = $file;
            }
            else {
                $categorizedFiles['general'][] = $file;
            }
        }

        //dd(count($categorizedFiles['archived']), count($categorizedFiles['general']));

        return view('files.index',[
                'archived' => $categorizedFiles['archived'],
                'general' => $categorizedFiles['general'],
                'virtualoffice' => $categorizedFiles['virtualoffice']
            ]
        );
    }


    public function generateArchiveNumber(Request $request, $fileId){
        $archive = Settings::where('key', 'file_archive_number')->first();
        $file = File::find($fileId);



        $archiveNr = $this->checkArchiveNumber($archive->value);
        $archive->value = $archiveNr;
        $file->archive_nr = 'A'.$archiveNr;
        $archive->save();
        $file->save();
    }

    private function checkArchiveNumber($number){
        $file = File::where('archive_nr', 'A'.$number)->first();

        if(isset($file->id)){
            $number = $number+1;
            return $this->checkArchiveNumber($number);
        } else {
            return $number;
        }
    }
}
