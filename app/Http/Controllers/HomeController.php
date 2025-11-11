<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Person;
use App\Models\PersonCompany;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $companies = Company::count();
        $persons = Person::count();
        $orders = Order::count();

        $user = auth()->user();

        $myOrdersPaid = Order::where('responsible_user_id', $user->id)->whereIn('payment_status', ['Paid'])->orderBy('created_at', 'desc')->get();

        $myOrdersNotPaid = Order::where('responsible_user_id', $user->id)->where('status', '<>', 'Cancelled')->whereIn('payment_status', ['Partially paid', 'Not paid'])->orderBy('created_at', 'desc')->get();

        return view('home',array(
            'totals' => array(
                'companies' => $companies, 'persons' => $persons, 'orders' => $orders
            ),
            'user' => $user,
            'myOrdersPaid' => $myOrdersPaid,
            'myOrdersNotPaid' => $myOrdersNotPaid
            )
        );
    }


    public function changelog(){

    }

    public function addMissingCompanies(){
        $results = DB::table('company_person')
            ->select('id', 'related_company')
            ->whereNotNull('related_company')
            ->get();
    }

    public function importData(){
        $puudu = array('companies' => array(),'persons' => array());
        $filename = storage_path()."/csv/query_result3.csv";

        $companies = array();

        $importCompanies = false;
        $importPersons = false;

        $importRelations = true;

        $importOrdersCompanies = false;
        $importOrdersPersons = false;

        if($importCompanies){
            // Open the file for reading
            if (($handle = fopen($filename, 'r')) !== FALSE) {

                // Loop through the file line by line
                while (($data = fgetcsv($handle, 50000, ',')) !== FALSE) {
                    $company = Company::where('name', 'LIKE', "%".substr_replace($data[0] ,"",-1)."%")->first();
                    if(!$company){
                        echo substr_replace($data[0] ,"",-1). ' puudu<br>';
                        $puudu['companies'][] = $data;
                    }
                    continue;

                    if($data[0] == 'Carr� d\'Or O�'){
                        $data[0] = 'Carr� d\'Or';
                    }

                    $companies[$data[0]]['registry_code'] = $data[1];
                    $companies[$data[0]]['registration_country_abbr'] = $data[9];
                    $companies[$data[0]]['registration_date'] = $data[2];
                    $companies[$data[0]]['vat'] = $data[3];
                    $companies[$data[0]]['capital'] = $data[4];

                    if($data[5] == 'address'){
                        if(!empty($data[7])){
                            $companies[$data[0]]['address']['address_street'] = $data[7];
                        }

                        if(!empty($data[8])){
                            $companies[$data[0]]['address']['address_city'] = $data[8];
                        }

                        if(!empty($data[10])){
                            $companies[$data[0]]['address']['address_zip'] = $data[10];
                        }
                    }



                    if($data[5] == 'ariregistriaddres'){
                        if(!empty($data[6])){
                            $companies[$data[0]]['address']['address_street_ariregister'] = $data[7];
                        }
                    }

                    if($data[5] == 'email'){
                        if(!empty($data[6])){
                            $companies[$data[0]]['emails']['email'] = $data[6];
                        }
                    }

                    if($data[5] == 'notificationemail'){
                        if(!empty($data[6])){
                            $companies[$data[0]]['emails']['notificationemail'] = $data[6];
                        }
                    }

                    if(!empty($data[11])){
                        $companies[$data[0]]['notes']['Delivery person'] = $data[11];
                    }

                    if(!empty($data[12])){
                        $companies[$data[0]]['notes']['Delivery email'] = $data[12];
                    }

                    if(!empty($data[13])){
                        $companies[$data[0]]['notes']['Delivery phone'] = $data[13];
                    }

                    if(!empty($data[14])){
                        $companies[$data[0]]['notes']['notes'] = $data[14];
                    }
                }

                // Close the file
                fclose($handle);
            }

            foreach($companies as $name => $company) {
                $registry_code = $company['registry_code'];
                $reg_country = $company['registration_country_abbr'];
                $reg_date = $company['registration_date'];
                $vat = $company['vat'];
                $capital = $company['capital'];


                if(isset($company['address'])){
                    if(isset($company['address']['address_street'])){
                        $address_street = $company['address']['address_street'];
                    }
                    if(isset($company['address']['address_city'])){
                        $address_city = $company['address']['address_city'];
                    }
                    if(isset($company['address']['address_zip'])){
                        $address_zip = $company['address']['address_zip'];
                    }
                }

                if(isset($company['emails'])){
                    if(isset($company['emails']['email'])){
                        $email = $company['emails']['email'];
                    }
                }

                if(isset($company['notes']['notes'])){
                    $notes = $company['notes']['notes']."\n<br>";
                } else {
                    $notes = '';
                }


                if(isset($company['address']['address_street_ariregister'])){
                    $notes .= '�riregistri aadress: '.$company['address']['address_street_ariregister']."\n<br>";
                }

                if(isset($company['emails']['notificationemail'])){
                    $notes .= 'Notification email: '.$company['emails']['notificationemail']."\n<br>";
                }

                if(isset($company['notes']['Delivery person'])){
                    $notes .= 'Delivery person: '.$company['notes']['Delivery person']."\n<br>";
                }

                if(isset($company['notes']['Delivery email'])){
                    $notes .= 'Delivery email: '.$company['notes']['Delivery email']."\n<br>";
                }

                if(isset($company['notes']['Delivery phone'])){
                    $notes .= 'Delivery phone: '.$company['notes']['Delivery phone']."\n<br><br>";
                }
            }
        }

        if($importPersons){
            $filename = storage_path()."/csv/query_result4.csv";
            $companies = array();

            // Open the file for reading
            if (($handle = fopen($filename, 'r')) !== FALSE) {

                // Loop through the file line by line
                while (($data = fgetcsv($handle, 10000, ',')) !== FALSE) {

                    $companies[$data[0]]['name'] = $data[0];
                    $companies[$data[0]]['registration_country_abbr'] = $data[1];
                    $companies[$data[0]]['id_code_ee'] = $data[2];
                    $companies[$data[0]]['id_code_other_country'] = $data[3];
                    $companies[$data[0]]['id_code_other'] = $data[4];
                    $companies[$data[0]]['birthdate'] = $data[5];

                    if($data[6] == 'address'){
                        if(!empty($data[8])){
                            $companies[$data[0]]['address']['address_street'] = $data[7];
                        }

                        if(!empty($data[9])){
                            $companies[$data[0]]['address']['address_city'] = $data[8];
                        }

                        if(!empty($data[9])){
                            $companies[$data[0]]['address']['address_country'] = $data[8];
                        }

                        if(!empty($data[11])){
                            $companies[$data[0]]['address']['address_zip'] = $data[10];
                        }
                    }


                    if($data[6] == 'email'){
                        if(!empty($data[7])){
                            $companies[$data[0]]['emails']['email'] = $data[7];
                        }
                    }

                    if($data[5] == 'mobile' || $data[5] == 'phone'){
                        if(!empty($data[6])){
                            $companies[$data[0]]['phone'] = $data[6];
                        }
                    }


                    if(!empty($data[12])){
                        $companies[$data[0]]['notes']['Delivery email'] = $data[12];
                    }

                    if(!empty($data[13])){
                        $companies[$data[0]]['notes']['Delivery phone'] = $data[13];
                    }

                    if(!empty($data[14])){
                        $companies[$data[0]]['notes']['notes'] = $data[14];
                    }
                }

                // Close the file
                fclose($handle);
            }

            $count = 0;

            foreach($companies as $name => $company) {
                $reg_country = $company['registration_country_abbr'];

                if(isset($company['address'])){
                    if(isset($company['address']['address_street'])){
                        $address_street = addslashes($company['address']['address_street']);
                    }
                    if(isset($company['address']['address_city'])){
                        $address_city = $company['address']['address_city'];
                    }
                    if(isset($company['address']['address_zip'])){
                        $address_zip = $company['address']['address_zip'];
                    }
                }

                if(isset($company['emails'])){
                    if(isset($company['emails']['email'])){
                        $email = $company['emails']['email'];
                    }
                }

                if(isset($company['notes']['notes'])){
                    $notes = $company['notes']['notes']."\n<br>";
                } else {
                    $notes = '';
                }


                if(isset($company['address']['address_street_ariregister'])){
                    $notes .= '�riregistri aadress: '.$company['address']['address_street_ariregister']."\n<br>";
                }

                if(isset($company['emails']['notificationemail'])){
                    $notes .= 'Notification email: '.$company['emails']['notificationemail']."\n<br>";
                }

                if(isset($company['notes']['Delivery person'])){
                    $notes .= 'Delivery person: '.$company['notes']['Delivery person']."\n<br>";
                }

                if(isset($company['notes']['Delivery email'])){
                    $notes .= 'Delivery email: '.$company['notes']['Delivery email']."\n<br>";
                }

                if(isset($company['notes']['Delivery phone'])){
                    $notes .= 'Delivery phone: '.$company['notes']['Delivery phone']."\n<br><br>";
                }

                if(isset($company['id_code_ee'])){
                    $id_code = $company['id_code_ee'];
                } elseif(isset($company['id_code_other'])){
                    $id_code = $company['id_code_other'];
                }

                $dateOfBirth = $company['birthdate'];

                if(isset($company['address'])){
                    if(isset($company['address']['address_country'])){
                        $country = $company['address']['address_country'];
                    }
                }

                if(isset($company['phone'])){
                    $phone = $company['phone'];
                }

                if(!isset($address_street)){
                    $address_street = '';
                }

                if(!isset($address_city)){
                    $address_city = '';
                }

                if(!isset($address_zip)){
                    $address_zip = '';
                }

                if(!isset($country)){
                    $country = '';
                }

                if(!isset($email)){
                    $email = '';
                }

                if(!isset($phone)){
                    $phone = '';
                }

                $query = "INSERT INTO persons (name, address_street, address_city, address_zip, address_dropdown, id_code, date_of_birth, country, email, phone, notes) VALUES ('$name', '$address_street', '$address_city', '$address_zip', '$reg_country', '$id_code', '$dateOfBirth', '$country', '$email', '$phone', '$notes');";

                $personModel = new Person();
                $personModel->name = $name;
                $personModel->address_street = $address_street;
                $personModel->address_city = $address_city;
                $personModel->address_zip = $address_zip;
                $personModel->address_dropdown = $reg_country;
                $personModel->id_code = $id_code;
                $personModel->date_of_birth = $dateOfBirth;
                $personModel->country = $country;
                $personModel->email = $email;
                $personModel->phone = $phone;
                $personModel->notes = $notes;

                try {
                    $personModel->save();
                } catch (\Illuminate\Database\QueryException $ex) {
                    //dd($ex->getMessage());

                    $count++;
                    echo $query;
                }
            }
        }

        if($importRelations){
            $puudu = array();
            $filename = storage_path()."/csv/query_result2.csv";

            $personCompany = new PersonCompany();

            if (($handle = fopen($filename, 'r')) !== FALSE) {

                // Loop through the file line by line
                while (($data = fgetcsv($handle, 20000, ',')) !== FALSE) {

                    $searchTerm = $data[0];
                    //$searchTerm = mb_convert_encoding($searchTerm, 'utf-8', 'ISO-8859-1');

                    $searchTerm1 = $data[1];
                    //$searchTerm1 = mb_convert_encoding($searchTerm1, 'utf-8', 'ISO-8859-1');

                    $company = Company::where('name', $searchTerm)->first();
                    $person = Person::where('name', $searchTerm1)->first();
                    $relation = $data[2];

                    //Puuduolevad persons
                    if(!$person){
                        //print_r($data);
                        $puudu['persons'][] = $data[1];
                        continue;
                    }

                    //Puuduolevad companies
                    if(!$company){
                        //print_r($data);
                        $puudu['companies'][] = $data[0];
                        //$company = Company::create(['name' => $data[0]]);
                        //dd($company);
                        continue;
                    }

                    $personCompany = new PersonCompany();

                    if(!$person){
                        $personCompany->related_company = $searchTerm1;
                    } else {
                        $personCompany->person_id = $person->id;
                    }



                    $personCompany->company_id = $company->id;
                    $personCompany->relation = $relation;
                    //$personCompany->save();

                }

            }

            //print_r($puudu);

        }

        if($importOrdersCompanies){
            $filename = storage_path()."/csv/query_result1.csv";

            $puudu = array();
            if (($handle = fopen($filename, 'r')) !== FALSE) {

                // Loop through the file line by line
                while (($data = fgetcsv($handle, 10000, ',')) !== FALSE) {
                    $companyName = $data[0];
                    $name = $data[1];
                    $created_date = $data[2];
                    $status = $data[3];


                    $company = Company::where('name', 'LIKE', "%".substr_replace($data[0] ,"",-1)."%")->first();

                    if(!$company){
                        $puudu['companies'][] = $company;
                        continue;
                    }

                    $order = new Order();
                    $order->name = $name;
                    $order->responsible_user_id = 2;
                    $order->status = $status;
                    $order->payment_status = '-';
                    $order->notification_sent = 0;
                    $order->created_at = $created_date;
                    $order->company_id = $company->id;
                    $order->save();

                }



            }
        }
        //1925 on

        if($importOrdersPersons){
            $filename = storage_path()."/csv/query_result5.csv";

            $puudu = array();
            if (($handle = fopen($filename, 'r')) !== FALSE) {

                // Loop through the file line by line
                while (($data = fgetcsv($handle, 20000, ',')) !== FALSE) {
                    $personName = $data[3];
                    $name = $data[0];
                    $created_date = $data[1];
                    $status = $data[2];

                    $person = Person::where('name', $personName)->first();

                    if(!$person){
                        $puudu[] = $personName;
                        continue;
                    }

                    $order = new Order();
                    $order->name = $name;
                    $order->responsible_user_id = 2;
                    $order->status = $status;
                    $order->payment_status = '-';
                    $order->notification_sent = 0;
                    $order->created_at = $created_date;
                    $order->person_id = $person->id;

                    try {
                        $order->save();
                    } catch (Exception $exception) {
                        //dd($exception->getMessage());

                        //$count++;
                        echo $query;
                    }

                }

            }
        }

        $puudu['companies'] = array_unique($puudu['companies']);
        //$puudu['persons'] = array_unique($puudu['persons']);

        echo "puudu companies: ".count($puudu['companies'])."<br>";
        //echo "puudu persons: ".count($puudu['persons'])."<br>";

        dd($puudu['companies']);
        echo implode('<br>', $puudu['companies'] );
        echo "<br><br>";
        //echo implode('<br>', $puudu['persons'] );

        dd("the end");
    }

    public function manualSQL()
    {
        /*$results = DB::select("SELECT services.name, services.cost, service_category.name as service_category FROM services
inner join service_category on service_category.id = services.service_category_id");*/
        $results = DB::select("select order_service.order_id, o.company_id from order_service inner join services s on s.id = order_service.service_id inner join orders o on order_service.order_id = o.id inner join payments p on p.order_id = o.id where order_service.service_id in (?,?,?) and (STR_TO_DATE( CASE WHEN p.paid_date LIKE '%.%' THEN STR_TO_DATE(p.paid_date, '%d.%m.%Y') ELSE STR_TO_DATE(p.paid_date, '%d-%m-%Y') END, '%Y-%m-%d' ) < ? AND STR_TO_DATE( CASE WHEN p.paid_date LIKE '%.%' THEN STR_TO_DATE(p.paid_date, '%d.%m.%Y') ELSE STR_TO_DATE(p.paid_date, '%d-%m-%Y') END, '%Y-%m-%d' ) > ? or o.paid_date <? and o.paid_date > ?) ORDER BY `order_id` DESC ", [19, 20, 21, '2024-06-01', '2023-05-31', '2024-06-01', '2023-05-31']);

        foreach($results as $key => $row){
            //Boardmemeber
            $companyMainContact = DB::select("select person_id from company_person where company_id = ? and relation = ?", [$row->company_id, 'Board Member']);
            $results[$key]->board_member = '';
            if($companyMainContact){
                foreach($companyMainContact as $boardmember){
                    $companyMainContactId = $boardmember->person_id;

                    if($companyMainContactId){
                        $personQuery = "select * from persons where id = ?";
                        $personInfo = DB::select($personQuery, [$companyMainContactId]);
                        $results[$key]->board_member .= "Name: ".$personInfo[0]->name."\n";
                        $results[$key]->board_member .= "Date of birth: ".$personInfo[0]->date_of_birth."\n";
                        $results[$key]->board_member .= "ID code: ".$personInfo[0]->id_code."\n";
                        $results[$key]->board_member .= "Email: ".$personInfo[0]->email."\n";
                        $results[$key]->board_member .= "Phone: ".$personInfo[0]->phone."\n";
                        $results[$key]->board_member .= "Address:".$personInfo[0]->address_street." ".$personInfo[0]->address_city."\n";

                        $riskitase = DB::select("select risk_level from entity_risks where person_id = ?", [$companyMainContactId]);
                        if($riskitase){
                            $results[$key]->board_member .= "Risk: ".$riskitase[0]->risk_level."\n";
                        }
                        $results[$key]->board_member .= "\n";
                    }
                }
            }

            //UBO
            $companyMainContact = DB::table('company_person')
                ->select('person_id', 'relation')
                ->where('company_id', $row->company_id)
                ->where('relation', 'LIKE', '%UBO%')
                ->get();
            $results[$key]->UBO = '';
            if($companyMainContact){
                foreach($companyMainContact as $boardmember){
                    $companyMainContactId = $boardmember->person_id;

                    if($companyMainContactId){
                        $personQuery = "select * from persons where id = ?";
                        $personInfo = DB::select($personQuery, [$companyMainContactId]);
                        $results[$key]->UBO .= $personInfo[0]->name."\n";
                        $results[$key]->UBO .= $boardmember->relation."\n";
                        $results[$key]->UBO .= "Date of birth: ".$personInfo[0]->date_of_birth."\n";
                        $results[$key]->UBO .= "ID code: ".$personInfo[0]->id_code."\n";
                        $results[$key]->UBO .= "Email: ".$personInfo[0]->email."\n";
                        $results[$key]->UBO .= "Phone: ".$personInfo[0]->phone."\n";
                        $results[$key]->UBO .= "Address: ".$personInfo[0]->address_street." ".$personInfo[0]->address_city."\n";

                        $riskitase = DB::select("select risk_level from entity_risks where person_id = ?", [$companyMainContactId]);
                        if($riskitase){
                            $results[$key]->UBO .= "Risk: ".$riskitase[0]->risk_level."\n";
                        }
                        $results[$key]->UBO .= "\n";
                    }
                }
            }


            //List of services
            $services = DB::select("SELECT s.cost, s.name
            FROM order_service os
            inner join services s on os.service_id = s.id
            WHERE order_id = ?", [$row->order_id]);

            $results[$key]->services = '';
            if($services){
                foreach($services as $service){
                    $results[$key]->services .= 'name:'.$service->name."\n";
                    $results[$key]->services .= 'cost:'.$service->cost."\n\n";
                }
            }


            //dd($services);
        }

        //dd($results);

        $results = json_decode(json_encode($results), true);

        // Define the CSV filename
        $filename = "payments_export.csv";

        // Create a file pointer to output stream
        $handle = fopen('php://output', 'w');

        // Set headers for the CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        // Get the array keys for the CSV header
        if (!empty($results)) {
            fputcsv($handle, array_keys($results[0]));
        }

        // Loop through each row and output it to the CSV
        foreach ($results as $row) {
            fputcsv($handle, $row);
        }

        // Close the file pointer
        fclose($handle);

        // Ensure the script stops after outputting CSV
        exit;
    }

    public function report(){
        $companies = Company::all();

        echo "<b>1. Companies with 0 documents:</b><br>";

        foreach ($companies as $company){
            if(count($company->files) == 0){
                echo "<a href='/companies/".$company->id."'>".$company->name."</a><br>";
            }
        }

        echo "<br><br>";

        echo "<b>2. Companies with 0 orders/cases:</b><br>";

        foreach ($companies as $company){
            if(count($company->orders) == 0){
                echo "<a href='/companies/".$company->id."'>".$company->name."</a><br>";
            }
        }

        echo "<br><br>";

        echo "<b>3. Orders with 0 documents:</b><br>";

        foreach (Order::all() as $order){
            if(count($order->files) == 0){
                echo "<a href='/orders/".$order->id."'>".$order->name."</a><br>";
            }
        }

        echo "<br><br>";

        echo "<b>4. Companies with 0 relations:</b><br>";

        foreach ($companies as $company){

            $relatedCompaniesQuery = PersonCompany::where('related_company', $company->id)->whereNull('person_id')->get();
            $relatedCompaniesQuery2 = PersonCompany::where('company_id', $company->id)->whereNull('person_id')->get();

            if(count($company->persons) == 0 && count($relatedCompaniesQuery) == 0 && count($relatedCompaniesQuery2) == 0){
                echo "<a href='/companies/".$company->id."'>".$company->name."</a><br>";
            }
        }
    }

    public function getKYC(Request $request)
    {
        $result = $request->name;
        $html = file_get_contents('https://fiu.ee/rahvusvahelised-sanktsioonid/rahvusvahelised-finantssanktsioonid?search='.$request->name);
        //$result = "Data processed successfully";
        return response()->json($html);
    }

}
