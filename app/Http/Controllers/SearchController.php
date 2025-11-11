<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\File;
use App\Models\Order;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $category = $request->get('category');
        $keyword = $request->get('s');

        $keyword = trim(strtok($keyword, '('));

        if($category == 'all'){
            return view('search.results',[
                    'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(20)->get(),
                    'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(20)->get(),
                    'orders' => Order::where('name','LIKE','%'.$keyword.'%')->limit(20)->get()
                ]
            );
        } else if($category == 'companies'){
            return view('search.results',[
                    'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(20)->get(),
                    'persons' => array()
                ]
            );
        } else if($category == 'persons'){
            return view('search.results',[
                    'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(20)->get(),
                    'companies' => array()
                ]
            );
        }

        return view('search.results');
    }

    public function detailedSearch(Request $request) {
        $searchTypes = $request->get('search_types', []);
        $filters = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'registry_code' => $request->get('registry_code'),
            'vat' => $request->get('vat'),
            'risk_level' => $request->get('risk_level'),
            'country' => $request->get('country'),
            'birthplace_country' => $request->get('birthplace_country'),
            'citizenship_country' => $request->get('citizenship_country'),
            'tax_residency_country' => $request->get('tax_residency_country'),
            'pep_status' => $request->get('pep_status'),
            'kyc_status' => $request->get('kyc_status'),
        ];

        $companies = [];
        $persons = [];

        // Search companies if selected
        if (in_array('companies', $searchTypes)) {
            $companyQuery = Company::query();

            // Apply filters
            if (!empty($filters['name'])) {
                $companyQuery->where('name', 'LIKE', '%' . $filters['name'] . '%');
            }
            if (!empty($filters['email'])) {
                $companyQuery->where('email', 'LIKE', '%' . $filters['email'] . '%');
            }
            if (!empty($filters['phone'])) {
                $companyQuery->where('phone', 'LIKE', '%' . $filters['phone'] . '%');
            }
            if (!empty($filters['address'])) {
                $companyQuery->where(function($query) use ($filters) {
                    $query->where('address_street', 'LIKE', '%' . $filters['address'] . '%')
                        ->orWhere('address_city', 'LIKE', '%' . $filters['address'] . '%')
                        ->orWhere('address_zip', 'LIKE', '%' . $filters['address'] . '%');
                });
            }
            if (!empty($filters['registry_code'])) {
                $companyQuery->where('registry_code', 'LIKE', '%' . $filters['registry_code'] . '%');
            }
            if (!empty($filters['vat'])) {
                $companyQuery->where('vat', 'LIKE', '%' . $filters['vat'] . '%');
            }
            if (!empty($filters['country'])) {
                $companyQuery->where(function($query) use ($filters) {
                    $query->where('registration_country', 'LIKE', '%' . $filters['country'] . '%')
                        ->orWhere('registration_country_abbr', 'LIKE', '%' . $filters['country'] . '%')
                        ->orWhere('address_dropdown', 'LIKE', '%' . $filters['country'] . '%');
                });
            }

            // Tax residency country filter for companies
            if (!empty($filters['tax_residency_country'])) {
                $companyQuery->where('tax_residency', 'LIKE', '%' . $filters['tax_residency_country'] . '%');
            }

            // Risk level filter for companies
            if (!empty($filters['risk_level'])) {
                $companyQuery->whereHas('getCurrentRisk', function($query) use ($filters) {
                    $query->where('risk_level', 'LIKE', '%' . $filters['risk_level'] . '%');
                });
            }

            // KYC Status filter for companies
            if (!empty($filters['kyc_status'])) {
                $this->applyKycFilter($companyQuery, $filters['kyc_status']);
            }

            $companies = $companyQuery->limit(50)->get();
        }

        if (in_array('persons', $searchTypes)) {
            $personQuery = Person::query();

            // Apply filters
            if (!empty($filters['name'])) {
                $personQuery->where('name', 'LIKE', '%' . $filters['name'] . '%');
            }
            if (!empty($filters['email'])) {
                $personQuery->where('email', 'LIKE', '%' . $filters['email'] . '%');
            }
            if (!empty($filters['phone'])) {
                $personQuery->where('phone', 'LIKE', '%' . $filters['phone'] . '%');
            }
            if (!empty($filters['address'])) {
                $personQuery->where(function($query) use ($filters) {
                    $query->where('address_street', 'LIKE', '%' . $filters['address'] . '%')
                        ->orWhere('address_city', 'LIKE', '%' . $filters['address'] . '%')
                        ->orWhere('address_zip', 'LIKE', '%' . $filters['address'] . '%');
                });
            }
            if (!empty($filters['registry_code'])) {
                $personQuery->where('id_code', 'LIKE', '%' . $filters['registry_code'] . '%');
            }
            if (!empty($filters['country'])) {
                $personQuery->where(function($query) use ($filters) {
                    $query->where('country', 'LIKE', '%' . $filters['country'] . '%')
                        ->orWhere('address_dropdown', 'LIKE', '%' . $filters['country'] . '%');
                });
            }

            // Birthplace country filter for persons
            if (!empty($filters['birthplace_country'])) {
                $personQuery->where('birthplace_country', 'LIKE', '%' . $filters['birthplace_country'] . '%');
            }

            // Citizenship country filter for persons
            if (!empty($filters['citizenship_country'])) {
                $personQuery->where('citizenship', 'LIKE', '%' . $filters['citizenship_country'] . '%');
            }

            // Tax residency country filter for persons
            if (!empty($filters['tax_residency_country'])) {
                $personQuery->whereHas('taxResidencies', function($query) use ($filters) {
                    $query->where('country', 'LIKE', '%' . $filters['tax_residency_country'] . '%')
                          ->active(); // Only search in active tax residencies
                });
            }

            // Risk level filter for persons
            if (!empty($filters['risk_level'])) {
                $personQuery->whereHas('getCurrentRisk', function($query) use ($filters) {
                    $query->where('risk_level', 'LIKE', '%' . $filters['risk_level'] . '%');
                });
            }

            // PEP Status filter (persons only)
            if ($filters['pep_status'] !== '' && $filters['pep_status'] !== null) {
                $personQuery->where('pep', $filters['pep_status']);
            }

            // KYC Status filter for persons
            if (!empty($filters['kyc_status'])) {
                $this->applyKycFilter($personQuery, $filters['kyc_status']);
            }

            $persons = $personQuery->limit(50)->get();
        }

        return view('search.detailed-results', [
            'companies' => $companies,
            'persons' => $persons,
            'filters' => $filters,
            'searchTypes' => $searchTypes
        ]);
    }

    /**
     * Apply KYC status filter to query
     */
    private function applyKycFilter($query, $kycStatus)
    {
        switch ($kycStatus) {
            case 'active':
                $query->whereHas('getCurrentKyc', function($subQuery) {
                    $subQuery->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now()->toDateString());
                    });
                });
                break;
                
            case 'expired':
                $query->whereHas('getCurrentKyc', function($subQuery) {
                    $subQuery->where('end_date', '<', now()->toDateString());
                });
                break;
                
            case 'no_kyc':
                $query->whereDoesntHave('kycs');
                break;
        }
    }

    public function showDetailedSearchForm() {
        return view('search.detailed-form');
    }

    public function autoComplete(Request $request){
        $category = $request->get('category');
        $keyword = $request->get('s');

        if($category == 'companies'){
            $data = [
                'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(5)->get(),
                'persons' => array()
            ];
        } else if($category == 'persons'){
            $data = [
                'companies' => array(),
                'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
            ];
        } else {
            $data = [
                'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(5)->get(),
                'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get(),
                'orders' => Order::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
            ];
        }

        $output = '<ul>';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'persons'){
                $icon = '<i class="fa-solid fa-user"></i>';
            } elseif ($class == 'companies'){
                $icon = '<i class="fa-solid fa-building"></i>';
            }elseif ($class == 'orders'){
                $icon = '<i class="fa-solid fa-file"></i>';
            }
            foreach($classData as $data){
                if($class == 'persons'){
                    $desc = '';
                    if($data->date_of_birth){
                        $desc = ' ('.$data->date_of_birth.')';
                    }
                    $output .= '
               <li>'.$icon.'</i><a href="/'.$class.'/'.$data->id.'">'.$data->name.$desc.'</a></li>
               ';
                } elseif ($class == 'companies'){
                    $desc = '';
                    if($data->registration_country_abbr){
                        $desc = ' ('.strtoupper($data->registration_country_abbr).')';
                    }

                    $output .= '
               <li>'.$icon.'</i><a href="/'.$class.'/'.$data->id.'">'.$data->name.$desc.'</a></li>
               ';
                } elseif ($class == 'orders'){
                    $desc = '';
                    $orderName = $data->name ?? '';
                    $output .= '
               <li>'.$icon.'</i><a href="/'.$class.'/'.$data->id.'">'.$orderName.$desc.'</a></li>
               ';
                }

            }
        }
        $output .= '</ul>';
        echo $output;

        //return response()->json($data);
    }

    public function autoCompleteModal(Request $request){
        $category = $request->get('category');
        $keyword = $request->get('s');

        if($category == 'companies'){
            $data = [
                'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(5)->get(),
                'persons' => array()
            ];
        } else if($category == 'persons'){
            $data = [
                'companies' => array(),
                'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
            ];
        } else {
            $data = [
                'companies' => Company::where('name','LIKE','%'.$keyword.'%')->limit(5)->get(),
                'persons' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
            ];
        }

        //dd($data);
        $output = '<ul>';
        $desc = '';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'persons'){
                $icon = '<i class="fa-solid fa-user"></i>';
            } elseif ($class == 'companies'){
                $icon = '<i class="fa-solid fa-building"></i>';
            }
            foreach($classData as $data){
                if($class == 'persons'){
                    if($data->date_of_birth){
                        $desc = ' ('.$data->date_of_birth.')';
                    }
                } elseif ($class == 'companies'){
                    if($data->registration_country_abbr){
                        $desc = ' ('.strtoupper($data->registration_country_abbr).')';
                    }
                }
                $output .= '
               <li class="modal-search-result" data-type="'.$class.'" data-id="'.$data->id.'" data-vat="'.$data->vat.'" data-street="'.$data->address_street.'" data-city="'.$data->address_city.'" data-zip="'.$data->address_zip.'" data-country="'.$data->address_dropdown.'" data-reg="'.$data->registry_code.'">'.$icon.'</i>'.$data->name. $desc.'</li>
               ';
            }
        }
        $output .= '</ul>';
        echo $output;

        //return response()->json($data);
    }

    public function autoCompleteModalUser(Request $request){
        $category = $request->get('category');
        $keyword = $request->get('s');


        $data = [
            'users' => User::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
        ];


        $output = '';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'users'){
                $icon = '<i class="fa-solid fa-user-tie"></i>';
            }

            foreach($classData as $data){

                $output .= '
               <li class="modal-search-result" data-id="'.$data->id.'">'.$icon.'</i>'.$data->name.'</li>
               ';
            }
        }
        $output .= '</ul>';
        echo $output;

        //return response()->json($data);
    }

    public function autoCompleteModalPerson(Request $request){
        $keyword = $request->get('s');

        $data = [
            'users' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
        ];

        $output = '';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'users'){
                $icon = '<i class="fa-solid fa-user"></i>';
            }

            foreach($classData as $data){

                $output .= '
               <li class="modal-search-result" data-email="'.$data->email.'" data-id="'.$data->id.'">'.$icon.'</i>'.$data->name.'</li>
               ';
            }
        }
        $output .= '</ul>';
        echo $output;

        //return response()->json($data);
    }

    public function searchDocuments(Request $request){
        $category = $request->get('category');
        $keyword = $request->get('s');

        //$keyword = trim(strtok($keyword, '(')); ???

        if($category == 'all'){
            return view('files.index',[
                    'archived' => File::where('name','LIKE','%'.$keyword.'%')->whereNotNull('archive_nr')->get(),
                    'general' => File::where('name','LIKE','%'.$keyword.'%')->whereNull('archive_nr')->get(),
                    'virtualoffice' => File::where('name','LIKE','%'.$keyword.'%')->where('virtual_office', '1')->get(),
                ]
            );
        } else if($category == 'archived'){
            return view('files.index',[
                    'archived' => File::where('name','LIKE','%'.$keyword.'%')->whereNotNull('archive_nr')->get(),
                    'general' => array(),
                    'virtualoffice' => array()
                ]
            );
        } else if($category == 'general'){
            return view('files.index',[
                    'general' => File::where('name','LIKE','%'.$keyword.'%')->whereNull('archive_nr')->get(),
                    'archived' => array(),
                    'virtualoffice' => array()
                ]
            );
        } else if($category == 'virtualoffice'){
            return view('files.index',[
                    'general' => array(),
                    'archived' => array(),
                    'virtualoffice' => File::where('name','LIKE','%'.$keyword.'%')->where('virtual_office', '1')->get()
                ]
            );
        }

        return view('files.index',[
                'archived' => [],
                'general' => [],
                'virtualoffice' => []
            ]
        );
    }
}
