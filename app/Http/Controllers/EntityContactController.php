<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EntityContact;
use App\Models\Person;
use App\Models\PersonCompany;
use Illuminate\Http\Request;

class EntityContactController extends Controller
{
    public function getEntityContacts(Request $request){
        if($request->entity == 'person'){
            $person = Person::find($request->entity_id);
            $contacts = '<br><input type="radio" id="main" name="emails" value="'.$person->email.'"> <label for="main">'.$person->email.'</label><br>';
            foreach($person->getContacts as $contact){
                $contacts .= '<input type="radio" id="other-'.$contact->id.'" name="emails" value="'.$contact->value.'"> <label for="main">'.$contact->value.'</label><br>';
            }
            echo $contacts;
        }

        if($request->entity == 'company'){
            $person = Company::find($request->entity_id);
            $contacts = '<br><input type="radio" id="main" name="emails" value="'.$person->email.'"> <label for="main">'.$person->email.'</label><br>';
            foreach($person->getContacts as $contact){
                $contacts .= '<input type="radio" id="other-'.$contact->id.'" name="emails" value="'.$contact->value.'"> <label for="main">'.$contact->value.'</label><br>';
            }
            echo $contacts;
        }
    }

    public function updateEntityContact(Request $request, $contactId){
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
    }


    public function deleteEntityContact($contactId){
        $entityContact = EntityContact::find($contactId);
        $entityContact->delete();
    }
}
