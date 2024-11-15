<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\excusesRequest;
use App\Models\excusesModel;
use App\Models\register_teacher;

class excusesController extends Controller
{
    // show create Excuse for teacher form
    public function create($id)
    {
        $teacher = register_teacher::findOrFail($id);
        return view('Excuse.create_form', ['teacher' => $teacher]);
    }

    // store Excuse data
    public function store(excusesRequest $request, $id)
    {

        $adder = register_teacher::where('user_id', Auth::user()->id)
        ->first();

        $data = $request->validated();

        // Handle file upload
        $supportingDocumentPath = null;
        if ($request->hasFile('supporting_document')) {
            $data['supporting_document'] = $request->file('supporting_document')->store('excuses_files', 's3');
        }

        $data['added_by'] = $adder->id;
        $excuse = excusesModel::create($data);

        return redirect()->route('singleTeacher.show', $id);
    }

    // Show Edit Form
    public function edit($id)
    {
        $excuse = excusesModel::find($id);
        $teacherId = $excuse ? $excuse->teacher_id : null;
        
        return view('Excuse.edit_form', compact('teacherId', 'excuse'));
    }

    // Update Excuse Data
    public function update(excusesRequest $request, $id)
    {

        $adder = register_teacher::where('user_id', Auth::user()->id)
        ->first();

        $data = $request->validated();

        // Handle file upload
        $supportingDocumentPath = null;
        if ($request->hasFile('supporting_document')) {
            $data['supporting_document'] = $request->file('supporting_document')->store('excuses_files', 's3');
        }

        $data['edited_by'] = $adder->id;

        $excuse = excusesModel::findOrFail($id);
        $excuse->update($data);

        return redirect()->route('singleTeacher.show', $excuse->teacher_id);
    }

    // Delete Disciplinary action
    public function destroy($id)
{
    $excuse = excusesModel::findOrFail($id);
    $excuse->delete();

    return redirect()->back();
}
}
