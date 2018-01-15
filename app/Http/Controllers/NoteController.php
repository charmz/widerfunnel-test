<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NoteController extends Controller
{
	public function index()
    {
        $user_id = Auth::user()->id;
		return Note::where('user_id', $user_id)
			->orderBy('id', 'desc')->get();
	}

	public function store(Request $request)
    {
        $user_id = Auth::user()->id;
		$this->validate($request, [
			'body' => 'required|max:500'
		]);
		return Note::create(['body' => request('body'), 'user_id' => $user_id]);
	}

	public function edit(Request $request)
	{
		$this->validate($request, [
			'body' => 'required|max:500'
		]);
		$note = Note::findOrFail($request->id);
		$note->body = $request->body;
		$note->save();
	}

	public function destroy($id)
	{
		$note = Note::findOrFail($id);
		$note->delete();
	}
}
