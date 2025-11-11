<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EntityContact;
use App\Models\Person;
use App\Models\PersonCompany;
use Illuminate\Http\Request;

class EntityContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getEntityContacts(Request $request){
        // Validate input
        $request->validate([
            'entity' => 'required|in:person,company',
            'entity_id' => 'required|integer'
        ]);

        $output = '<br>';
        
        if($request->entity == 'person'){
            $person = Person::findOrFail($request->entity_id);
            $email = htmlspecialchars($person->email ?? '', ENT_QUOTES, 'UTF-8');
            $output .= '<input type="radio" id="main" name="emails" value="' . $email . '"> <label for="main">' . $email . '</label><br>';
            foreach($person->getContacts as $contact){
                $contactValue = htmlspecialchars($contact->value ?? '', ENT_QUOTES, 'UTF-8');
                $output .= '<input type="radio" id="other-' . (int)$contact->id . '" name="emails" value="' . $contactValue . '"> <label for="other-' . (int)$contact->id . '">' . $contactValue . '</label><br>';
            }
        }

        if($request->entity == 'company'){
            $company = Company::findOrFail($request->entity_id);
            $email = htmlspecialchars($company->email ?? '', ENT_QUOTES, 'UTF-8');
            $output .= '<input type="radio" id="main" name="emails" value="' . $email . '"> <label for="main">' . $email . '</label><br>';
            foreach($company->getContacts as $contact){
                $contactValue = htmlspecialchars($contact->value ?? '', ENT_QUOTES, 'UTF-8');
                $output .= '<input type="radio" id="other-' . (int)$contact->id . '" name="emails" value="' . $contactValue . '"> <label for="other-' . (int)$contact->id . '">' . $contactValue . '</label><br>';
            }
        }
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
    }

    public function updateEntityContact(Request $request, $contactId){
        $request->validate([
            'entity' => 'required|in:person,company',
            'type' => 'required|in:email,phone,taxResidency',
            'value' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
            'person_id' => 'nullable|integer|exists:persons,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'old_email' => 'nullable|string|max:255'
        ]);
        if($contactId == 0){
            if($request->entity == 'person'){
                $person = Person::find($request->person_id);
                if(!$person){
                    return response()->json(['error' => 'Person not found'], 404);
                }
                if($request->type == 'phone'){
                    $person->phone = $request->value;
                    $person->phone_note = $request->note;
                } elseif($request->type == 'taxResidency'){
                    $person->tax_residency = $request->value;
                }
                elseif($request->type == 'email'){
                    $person->email = $request->value;
                    $person->email_note = $request->note;
                    if($request->has('old_email')){
                        $personCompanies = PersonCompany::where('person_id', $request->person_id)->where('selected_email', $request->old_email)->get();
                        foreach($personCompanies as $personCompany){
                            $personCompany->selected_email = $request->value;
                            $personCompany->save();
                        }
                    }
                }
                $person->save();
            } elseif($request->entity == 'company'){
                $company = Company::find($request->company_id);
                if(!$company){
                    return response()->json(['error' => 'Company not found'], 404);
                }
                if($request->type == 'phone'){
                    $company->phone = $request->value;
                    $company->phone_note = $request->note;
                } elseif($request->type == 'taxResidency'){
                    $company->tax_residency = $request->value;
                } elseif($request->type == 'email'){
                    $company->email = $request->value;
                    $company->email_note = $request->note;
                }
                $company->save();
            }
        } else {
            $entityContact = EntityContact::find($contactId);
            if(!$entityContact){
                return response()->json(['error' => 'Entity contact not found'], 404);
            }
            if($request->type == 'email' && $request->entity == 'person' && $request->has('person_id') && $request->has('old_email')){
                $personCompanies = PersonCompany::where('person_id', $request->person_id)->where('selected_email', $request->old_email)->get();
                foreach($personCompanies as $personCompany){
                    $personCompany->selected_email = $request->value;
                    $personCompany->save();
                }
            }
            $entityContact->value = $request->value;
            $entityContact->note = $request->note;
            $entityContact->save();
        }
        
        return response()->json(['success' => true], 200);
    }

    public function addNewEntityContact(Request $request, $entityId){
        $request->validate([
            'entity' => 'required|in:person,company',
            'type' => 'required|in:email,phone',
            'value' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000'
        ]);
        
        $entityContact = new EntityContact;
        if($request->entity == 'person'){
            $entityContact->person_id = $entityId;
        } elseif($request->entity == 'company'){
            $entityContact->company_id = $entityId;
        }
        $entityContact->value = $request->value;
        $entityContact->type = $request->type;
        $entityContact->note = $request->note;
        $entityContact->save();
        
        return response()->json(['success' => true, 'message' => 'Contact added successfully']);
    }


    public function deleteEntityContact($contactId){
        $entityContact = EntityContact::findOrFail($contactId);
        $entityContact->delete();
        
        return response()->json(['success' => true, 'message' => 'Contact deleted successfully']);
    }
}
