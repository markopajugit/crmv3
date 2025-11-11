<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function newPersonNote(Request $request){
        $user = Auth::user();
        $note = new Note;
        $note->person_id = $request->person_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
    }

    public function newCompanyNote(Request $request){
        $user = Auth::user();
        $note = new Note;
        $note->company_id = $request->company_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
    }

    public function newOrderNote(Request $request){
        $user = Auth::user();
        $note = new Note;
        $note->order_id = $request->order_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
    }

    public function deleteNote(Request $request, $id){
        //dd($request);
        $note = Note::find($id);
        //dd($note);
        $note->delete();
    }

    public function updateNote(Request $request, $id){
        //dd($request);
        $note = Note::find($id);
        //dd($note);
        $note->content = $request->content;
        $note->save();
    }
}
