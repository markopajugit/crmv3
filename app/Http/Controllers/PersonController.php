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
    public function index()
    {
        $persons = Person::latest()->paginate(10);

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

        $person = Person::create($request->all());

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
        $person->update($request->all());
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
