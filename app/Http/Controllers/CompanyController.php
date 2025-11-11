<?php

namespace App\Http\Controllers;

use App\Models\CompanyOrder;
use App\Models\EntityRisk;
use App\Models\Person;
use App\Models\PersonCompany;
use App\Models\Company;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CompanyController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::latest()->paginate(10);

        return view('companies.index',compact('companies'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create',[
                'persons' => Person::all()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        if($request->number == 0){
            $companyNo = Settings::where('key', 'next_company_number')->first();
            $request['number'] = $companyNo->value;

            $companyNo->value = $companyNo->value + 1;
            $companyNo->save();
        }

        $company = Company::create($request->all());

        $company->persons()->sync($request->persons);

        return redirect()->route('companies.show', ['company' => $company])
            ->with('success','New Company created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $relatedCompaniesQuery = PersonCompany::where('company_id', $company->id)->whereNull('person_id')->get();
        $relatedCompanies = array();
        foreach($relatedCompaniesQuery as $relatedCompany){
            $companyFound = Company::find($relatedCompany->related_company);
            if(isset($companyFound->id)){
                $relatedCompanies[] = (object)array('id' => $companyFound->id, 'relation' => $relatedCompany->relation, 'name' => $companyFound->name, 'contact_deadline' => $relatedCompany->contact_deadline);
            } else {
                //See siin on special case kui ta ei leia companyt related_company järgi
                $relatedCompanies[] = (object)array('id' => $relatedCompany->related_company, 'relation' => $relatedCompany->relation, 'name' => 'Error - deleted company', 'contact_deadline' => $relatedCompany->contact_deadline);
            }
        }



        $relatedCompaniesQuery = PersonCompany::where('related_company', $company->id)->whereNull('person_id')->get();
        foreach($relatedCompaniesQuery as $relatedCompany){
            $companyFound = Company::find($relatedCompany->company_id);
            if(isset($companyFound->id)){
                $relatedCompanies[] = (object)array('id' => $companyFound->id, 'relation' => $company->name.' is '.$relatedCompany->relation, 'name' => $companyFound->name);
            }
        }


        foreach($company->persons as $key => $person){
            $relatedContactDeadline = PersonCompany::where('company_id', $person->pivot->company_id)->where('person_id', $person->pivot->person_id)->where('relation', 'Authorised contact person')->first();
            $company->persons[$key]->pivot->relation = str_replace(",", ", ", $person->pivot->relation);
            if(!empty($relatedContactDeadline->contact_deadline)){
                $company->persons[$key]->pivot->contact_deadline = $relatedContactDeadline->contact_deadline;
            }
        }

        $mainContact = PersonCompany::where('relation', 'Main Contact')->where('company_id', $company->id)->first();

        return view('companies.show',[
            'company' => $company,
            'persons' => Person::all(),
            'relatedCompanies' => $relatedCompanies,
            'mainContact' => $mainContact
            ]
        )->with('orders');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('companies.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        if (isset($request->number)) {
            //Check if inserted company number is unique
            $companyCheck = Company::where('number', $request->number)->first();
            if ($companyCheck && $companyCheck->id != $company->id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => ['number' => ['Company with that number already exists']]
                    ], 422);
                }
                return Redirect::back()->withErrors('Company with that number already exists');
            }
        }

        try {
            $company->update($request->all());
            $company->refresh();

            // If AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Company updated successfully',
                    'data' => [
                        'id' => $company->id,
                        'name' => $company->name,
                        'number' => $company->number,
                        'registry_code' => $company->registry_code,
                        'registration_date' => $company->registration_date,
                        'registration_country' => $company->registration_country,
                        'vat' => $company->vat,
                        'notes' => $company->notes,
                        'activity_code' => $company->activity_code,
                        'activity_code_description' => $company->activity_code_description,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => ['general' => [$e->getMessage()]]
                ], 422);
            }
            throw $e;
        }

        $relatedCompaniesQuery = PersonCompany::where('company_id', $company->id)->whereNull('person_id')->get();
        $relatedCompanies = array();
        foreach($relatedCompaniesQuery as $relatedCompany){
            $companyFound = Company::find($relatedCompany->related_company);
            if(isset($companyFound->id)){
                $relatedCompanies[] = (object)array('id' => $companyFound->id, 'relation' => $relatedCompany->relation, 'name' => $companyFound->name, 'contact_deadline' => $relatedCompany->contact_deadline);
            }
        }

        $relatedCompaniesQuery = PersonCompany::where('related_company', $company->id)->whereNull('person_id')->get();
        foreach($relatedCompaniesQuery as $relatedCompany){
            $companyFound = Company::find($relatedCompany->company_id);
            if(isset($companyFound->id)){
                $relatedCompanies[] = (object)array('id' => $companyFound->id, 'relation' => $companyFound->name.' is '.$relatedCompany->relation, 'name' => $companyFound->name);
            }
        }

        foreach($company->persons as $key => $person){
            $relatedContactDeadline = PersonCompany::where('company_id', $person->pivot->company_id)->where('person_id', $person->pivot->person_id)->where('relation', 'Authorised contact person')->first();
            $company->persons[$key]->pivot->relation = str_replace(",", ", ", $person->pivot->relation);
            if(!empty($relatedContactDeadline->contact_deadline)){
                $company->persons[$key]->pivot->contact_deadline = $relatedContactDeadline->contact_deadline;
            }
        }

        $mainContact = PersonCompany::where('relation', 'Main Contact')->where('company_id', $company->id)->first();

        return view('companies.show',[
                'company' => $company,
                'persons' => Person::all(),
                'relatedCompanies' => $relatedCompanies,
                'mainContact' => $mainContact
            ]
        )->with('orders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeRelatedPerson(Request $request)
    {
        if($request->type == 'person'){
            $relation = new PersonCompany();
            $relation->company_id = $request->company_id;
            $relation->person_id = $request->entity_id;
            $relation->relation = $request->relation;
            if($request->selected_email){
                $relation->selected_email = $request->selected_email;
            }
            if(!empty($request->contact_deadline) && $request->relation == 'Authorised contact person'){
                $relation->contact_deadline = $request->contact_deadline;
            }
            $relation->save();
        }

        if($request->type == 'company'){
            $relation = new PersonCompany();
            $relation->company_id = $request->company_id;
            $relation->related_company = $request->entity_id;
            $relation->relation = $request->relation;
            if(!empty($request->contact_deadline) && $request->relation == 'Authorised contact person'){
                $relation->contact_deadline = $request->contact_deadline;
            }
            $relation->save();
        }

        if($request->type == 'companytoperson'){
            $relation = new PersonCompany();
            $relation->company_id = $request->company_id;
            $relation->person_id = $request->person_id;
            $relation->relation = $request->relation;
            $relation->save();
        }

        if($request->type == 'persontoperson'){
            $relation = new PersonCompany();
            $relation->related_person = $request->company_id;
            $relation->person_id = $request->person_id;
            $relation->relation = $request->relation;
            $relation->save();
        }


        //dd($request);
        //PersonCompany::create($request->all());

        return redirect()->route('companies.index')
            ->with('success','Product created successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRelatedPerson(Request $request)
    {
        if($request->type == 'person'){
            $relation = PersonCompany::where([
                ['person_id', '=', $request->person_id],
                ['related_person', '=', $request->company_id],
                ['relation', '=', $request->relation]
            ])->first();



            if($relation){
                $relation->delete();
            }

            return redirect()->route('companies.index')
                ->with('success','Related Person deleted');

        }

        $relationString = str_replace(', ', ',', $request->relation);

        $relation = PersonCompany::where([
            ['person_id', '=', $request->person_id],
            ['company_id', '=', $request->company_id],
            ['relation', 'like', $relationString]
        ])->first();

        /*$relation1 = PersonCompany::where([
            ['company_id', '=', $request->company_id],
            ['relation', '=', $relation]
        ])->first();

        $relation2 = PersonCompany::where([
            ['person_id', '=', $request->person_id],
            ['relation', '=', $relation]
        ])->first();

        $relation3 = PersonCompany::where([
            ['person_id', '=', $request->person_id],
            ['company_id', '=', $request->company_id],
        ])->first();

        $relation4 = PersonCompany::where([
            ['person_id', '=', $request->person_id],
            ['company_id', '=', $request->company_id],
            ['relation', 'like', '%'.$relation.'%']
        ])->first();*/

        //dd($request);
        //dd($relation);

        if($relation){
            $relation->delete();
        }

        return redirect()->route('companies.index')
            ->with('success','Related Person deleted');
    }

    public function updateRelatedPerson(Request $request)
    {
        if($request->type == 'person'){
            $relation = PersonCompany::where([
                ['person_id', '=', $request->person_id],
                ['related_person', '=', $request->company_id],
            ])->first();

            if($relation){
                $relation->relation = $request->relation;
                $relation->save();
            }

            return redirect()->route('companies.index')
                ->with('success','Related Person edited');

        }

        $relation = PersonCompany::where([
            ['person_id', '=', $request->person_id],
            ['company_id', '=', $request->company_id]
        ])->first();


        if($relation){
            $relation->relation = $request->relation;
            $relation->save();
        }

        return redirect()->route('companies.index')
            ->with('success','Related Person edited');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRelatedCompany(Request $request)
    {
        $relation = PersonCompany::where([
            ['related_company', '=', $request->relatedCompany_id],
            ['company_id', '=', $request->company_id],
            ['relation', '=', $request->relation]
        ])->first();

        $relation->delete();

        return redirect()->route('companies.index')
            ->with('success','Related Person deleted');
    }

    public function updateCompanyRisk(Request $request){
        try {
            $entityRisk = new EntityRisk();
            $entityRisk->company_id = $request->company_id;
            $entityRisk->risk_level = $request->risk_level;
            $entityRisk->user_id = Auth::id();
            $entityRisk->save();

            $company = Company::find($request->company_id);
            $currentRisk = $company->getCurrentRisk();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Risk level updated successfully',
                    'data' => [
                        'risk_level' => $request->risk_level,
                        'risk_level_text' => $this->getRiskLevelText($request->risk_level),
                        'user_id' => Auth::id(),
                        'user_name' => Auth::user()->name,
                        'created_at' => $entityRisk->created_at->format('Y-m-d H:i:s'),
                    ]
                ]);
            }
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => ['general' => [$e->getMessage()]]
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Get risk level text from numeric value
     */
    private function getRiskLevelText($level)
    {
        $levels = [
            1 => 'Low',
            2 => 'Medium',
            3 => 'High'
        ];
        return $levels[$level] ?? 'Unknown';
    }
}
