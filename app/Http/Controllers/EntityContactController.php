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
                if($request->type == 'phone'){
                    $person->phone = $request->value;
                    $person->phone_note = $request->note;
                } elseif($request->type == 'taxResidency'){
                    $person->tax_residency = $request->value;
                }
                elseif($request->type='email'){
                    $person->email = $request->value;
                    $person->email_note = $request->note;
                    $personCompanies = PersonCompany::where('person_id', $request->person_id)->where('selected_email', $request->old_email)->get();
                    foreach($personCompanies as $personCompany){
                        $personCompany->selected_email = $request->value;
                        $personCompany->save();
                    }
                }
                $person->save();
            } elseif($request->entity == 'company'){
                $company = Company::find($request->company_id);
                if($request->type == 'phone'){
                    $company->phone = $request->value;
                    $company->phone_note = $request->note;
                } elseif($request->type == 'taxResidency'){
                    $company->tax_residency = $request->value;
                } elseif($request->type='email'){
                    $company->email = $request->value;
                    $company->email_note = $request->note;
                }
                $company->save();
            }
        } else {
            if($request->type == 'email' && $request->entity == 'person'){
                $personCompanies = PersonCompany::where('person_id', $request->person_id)->where('selected_email', $request->old_email)->get();
                foreach($personCompanies as $personCompany){
                    $personCompany->selected_email = $request->value;
                    $personCompany->save();
                }
            }
            $entityContact = EntityContact::find($contactId);
            $entityContact->value = $request->value;
            $entityContact->note = $request->note;
            $entityContact->save();
        }
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
