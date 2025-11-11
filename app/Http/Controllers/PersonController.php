<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EntityRisk;
use App\Models\Person;
use App\Models\PersonCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $persons = Person::latest()->paginate(10);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            $search = $request->get('search', '');
            
            $query = Person::query();
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('id_code', 'LIKE', '%' . $search . '%')
                      ->orWhere('id_code_est', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%')
                      ->orWhere('phone', 'LIKE', '%' . $search . '%')
                      ->orWhere('address_street', 'LIKE', '%' . $search . '%')
                      ->orWhere('address_city', 'LIKE', '%' . $search . '%')
                      ->orWhere('country', 'LIKE', '%' . $search . '%');
                });
            }
            
            $persons = $query->latest()->paginate(10);
            
            return response()->json([
                'html' => view('persons.partials.table', compact('persons'))->render(),
                'pagination' => view('persons.partials.pagination', compact('persons'))->render(),
                'total' => $persons->total()
            ]);
        }

        return view('persons.index',compact('persons'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('persons.create',[
                'companies' => Company::all()
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
        //dd($request);
        $request->validate([
            'name' => 'required'
        ]);

        // Only allow fillable fields to prevent mass assignment vulnerability
        $personData = $request->only([
            'name', 'address_street', 'address_city', 'address_zip', 'address_dropdown', 
            'id_code', 'id_code_est', 'email', 'phone', 'tax_residency', 'notes', 
            'date_of_birth', 'country', 'address_note', 'email_note', 'phone_note', 
            'birthplace_country', 'birthplace_city', 'citizenship', 'pep'
        ]);
        $person = Person::create($personData);

        $person->companies()->sync($request->companies);

        return redirect()->route('persons.show', ['person' => $person])
            ->with('success','Person created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $relatedPersonsFromDB = PersonCompany::where('person_id', $person->id)->where('company_id', null)->get();
        $relatedPersons = array();
        foreach($relatedPersonsFromDB as $relatedPerson){
            $relatedPersonData = Person::find($relatedPerson->related_person);
            $relatedPersons[] = array('relation' => $relatedPerson->relation, 'name' => $relatedPersonData->name, 'relation_id' => $relatedPerson->id, 'person_id' => $relatedPersonData->id);
        }

        $personRisk = $person->getCurrentRisk()->get();

        // Load tax residencies
        $person->load('taxResidencies');

        return view('persons.show',compact('person', 'relatedPersons'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        // Only allow fillable fields to prevent mass assignment vulnerability
        $personData = $request->only([
            'name', 'address_street', 'address_city', 'address_zip', 'address_dropdown', 
            'id_code', 'id_code_est', 'email', 'phone', 'tax_residency', 'notes', 
            'date_of_birth', 'country', 'address_note', 'email_note', 'phone_note', 
            'birthplace_country', 'birthplace_city', 'citizenship', 'pep'
        ]);
        $person->update($personData);
        $relatedPersonsFromDB = PersonCompany::where('person_id', $person->id)->where('company_id', null)->get();
        $relatedPersons = array();
        foreach($relatedPersonsFromDB as $relatedPerson){
            $relatedPersonData = Person::find($relatedPerson->related_person);
            $relatedPersons[] = array('relation' => $relatedPerson->relation, 'name' => $relatedPersonData->name, 'relation_id' => $relatedPerson->id, 'person_id' => $relatedPersonData->id);
        }
        return view('persons.show',[
                'person' => $person,
                'relatedPersons' => $relatedPersons
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::find($id);
        $person->delete();
    }

    public function updatePersonRisk(Request $request){
        $entityRisk = new EntityRisk();
        $entityRisk->person_id = $request->person_id;
        $entityRisk->risk_level = $request->risk_level;
        $entityRisk->user_id = Auth::id();
        $entityRisk->save();
    }
}
