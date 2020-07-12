<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\User;

class NoteController extends Controller
{
    public function index(Request $request) {
        $userId = $request->user()->id;
        $getNotes = Note::where('user_id', $userId)->get();

        return $getNotes;
    }

    public function show(Request $request, $id) {
        $userId = $request->user()->id;
        $getNote = User::find($userId)->notes()->where('id', $id)->first();
        
        if (!$getNote) {
            return response()->json(['error' => 'Note ID not found!'], 404);
        };

        return $getNote;
    }

    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required',
        ]);

        $createNote = $request->user()->notes()->create([
            'title' => $request->json('title'),
            'body'  => $request->json('body'),
        ]);

        return $createNote;
    }
}
