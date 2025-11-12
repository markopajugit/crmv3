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
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        // Validate and sanitize input
        $category = $request->get('category', '');
        $keyword = $request->get('s', '');
        
        // Sanitize keyword to prevent XSS
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
        
        // Validate category
        if (!in_array($category, ['companies', 'persons', ''])) {
            return response()->json(['error' => 'Invalid category'], 400);
        }

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
            foreach($classData as $item){
                if($class == 'persons'){
                    $desc = '';
                    if($item->date_of_birth){
                        $desc = ' (' . htmlspecialchars($item->date_of_birth, ENT_QUOTES, 'UTF-8') . ')';
                    }
                    $output .= '<li>' . $icon . '<a href="/' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '/' . (int)$item->id . '">' . htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') . $desc . '</a></li>';
                } elseif ($class == 'companies'){
                    $desc = '';
                    if($item->registration_country_abbr){
                        $desc = ' (' . htmlspecialchars(strtoupper($item->registration_country_abbr), ENT_QUOTES, 'UTF-8') . ')';
                    }
                    $output .= '<li>' . $icon . '<a href="/' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '/' . (int)$item->id . '">' . htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') . $desc . '</a></li>';
                } elseif ($class == 'orders'){
                    $desc = '';
                    $orderName = $item->name ?? '';
                    $output .= '<li>' . $icon . '<a href="/' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '/' . (int)$item->id . '">' . htmlspecialchars($orderName, ENT_QUOTES, 'UTF-8') . $desc . '</a></li>';
                }
            }
        }
        $output .= '</ul>';
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function autoCompleteModal(Request $request){
        // Validate and sanitize input
        $category = $request->get('category', '');
        $keyword = $request->get('s', '');
        
        // Sanitize keyword
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
        
        // Validate category
        if (!in_array($category, ['companies', 'persons', ''])) {
            return response()->json(['error' => 'Invalid category'], 400);
        }

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
            foreach($classData as $item){
                $desc = '';
                if($class == 'persons'){
                    if($item->date_of_birth){
                        $desc = ' (' . htmlspecialchars($item->date_of_birth, ENT_QUOTES, 'UTF-8') . ')';
                    }
                } elseif ($class == 'companies'){
                    if($item->registration_country_abbr){
                        $desc = ' (' . htmlspecialchars(strtoupper($item->registration_country_abbr), ENT_QUOTES, 'UTF-8') . ')';
                    }
                }
                // Escape all attributes to prevent XSS
                $output .= '<li class="modal-search-result" data-type="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . 
                    '" data-id="' . (int)$item->id . 
                    '" data-vat="' . htmlspecialchars($item->vat ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-street="' . htmlspecialchars($item->address_street ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-city="' . htmlspecialchars($item->address_city ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-zip="' . htmlspecialchars($item->address_zip ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-country="' . htmlspecialchars($item->address_dropdown ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-reg="' . htmlspecialchars($item->registry_code ?? '', ENT_QUOTES, 'UTF-8') . 
                    '">' . $icon . htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') . $desc . '</li>';
            }
        }
        $output .= '</ul>';
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function autoCompleteModalUser(Request $request){
        // Validate and sanitize input
        $keyword = $request->get('s', '');
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

        $data = [
            'users' => User::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
        ];

        $output = '<ul>';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'users'){
                $icon = '<i class="fa-solid fa-user-tie"></i>';
            }

            foreach($classData as $item){
                $output .= '<li class="modal-search-result" data-id="' . (int)$item->id . '">' . 
                    $icon . htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') . '</li>';
            }
        }
        $output .= '</ul>';
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function autoCompleteModalPerson(Request $request){
        // Validate and sanitize input
        $keyword = $request->get('s', '');
        $keyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');

        $data = [
            'users' => Person::where('name','LIKE','%'.$keyword.'%')->limit(5)->get()
        ];

        $output = '<ul>';
        foreach($data as $class => $classData)
        {
            $icon = '';
            if($class == 'users'){
                $icon = '<i class="fa-solid fa-user"></i>';
            }

            foreach($classData as $item){
                $output .= '<li class="modal-search-result" data-email="' . 
                    htmlspecialchars($item->email ?? '', ENT_QUOTES, 'UTF-8') . 
                    '" data-id="' . (int)$item->id . '">' . 
                    $icon . htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') . '</li>';
            }
        }
        $output .= '</ul>';
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
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
