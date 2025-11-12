<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newPersonNote(Request $request){
        $request->validate([
            'person_id' => 'required|integer|exists:persons,id',
            'content' => 'required|string|max:5000'
        ]);
        $user = Auth::user();
        $note = new Note;
        $note->person_id = $request->person_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
        
        return response()->json(['success' => true, 'message' => 'Note created successfully']);
    }

    public function newCompanyNote(Request $request){
        $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
            'content' => 'required|string|max:5000'
        ]);
        
        $user = Auth::user();
        $note = new Note;
        $note->company_id = $request->company_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
        
        return response()->json(['success' => true, 'message' => 'Note created successfully']);
    }

    public function newOrderNote(Request $request){
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'content' => 'required|string|max:5000'
        ]);
        
        $user = Auth::user();
        $note = new Note;
        $note->order_id = $request->order_id;
        $note->content = $request->content;
        $note->user_id = $user->id;
        $note->save();
        
        return response()->json(['success' => true, 'message' => 'Note created successfully']);
    }

    public function deleteNote(Request $request, $id){
        $user = Auth::user();
        $note = Note::findOrFail($id);
        
        // Verify user has permission (optional: add ownership check)
        $note->delete();
        
        return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
    }

    public function updateNote(Request $request, $id){
        $request->validate([
            'content' => 'required|string|max:5000'
        ]);
        
        $user = Auth::user();
        $note = Note::findOrFail($id);
        $note->content = $request->content;
        $note->save();
        
        return response()->json(['success' => true, 'message' => 'Note updated successfully']);
    }
}
