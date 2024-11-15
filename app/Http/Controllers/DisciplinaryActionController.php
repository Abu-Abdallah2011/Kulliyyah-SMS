<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\register_student;
use App\Models\register_teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\DisciplinaryActionModel;
use App\Http\Requests\DisciplinaryRequest;

class DisciplinaryActionController extends Controller
{
    // show create disciplinary action for teacher form
    public function create($id)
    {
        $teacher = register_teacher::findOrFail($id);
        return view('Discipline.create_form', ['teacher' => $teacher]);
    }

    // store disciplinary action data
    public function store(DisciplinaryRequest $request, $id)
    {

        $adder = register_teacher::where('user_id', Auth::user()->id)
        ->first();

        $data = $request->validated();

        // Handle file upload
        $schoolFilePath = null;
        if ($request->hasFile('school_file')) {
            $data['school_file'] = $request->file('school_file')->store('disciplinary_files', 's3');
        }

        $offenderFilePath = null;
        if ($request->hasFile('offenderFile')) {
            $data['offender_file'] = $request->file('offender_file')->store('disciplinary_files', 's3');
        }

        $data['added_by'] = $adder->fullname;
        $discipline = DisciplinaryActionModel::create($data);

        return redirect()->route('singleTeacher.show', $id)
        ->with('message', 'Disciplinary action created successfully.');
    }

    // Show Edit Form
    public function edit($id)
    {
        $action = DisciplinaryActionModel::find($id);
        $teacherId = $action ? $action->teacher_id : null;
        
        return view('Discipline.edit_form', compact('teacherId', 'action'));
    }

    // Update Diisciplinary action
    public function update(DisciplinaryRequest $request, $id)
    {

        $adder = register_teacher::where('user_id', Auth::user()->id)
        ->first();

        $data = $request->validated();

        // Handle file upload
        $schoolFilePath = null;
        if ($request->hasFile('school_file')) {
            $data['school_file'] = $request->file('school_file')->store('disciplinary_files', 's3');
        }

        $offenderFilePath = null;
        if ($request->hasFile('offenderFile')) {
            $data['offender_file'] = $request->file('offender_file')->store('disciplinary_files', 's3');
        }

        $data['edited_by'] = $adder->fullname;

        $discipline = DisciplinaryActionModel::findOrFail($id);
        $discipline->update($data);

        return redirect()->route('singleTeacher.show', $discipline->teacher_id)
                     ->with('message', 'Disciplinary action updated successfully.');
    }

    // Delete Disciplinary action
    public function destroy($id)
{
    $action = DisciplinaryActionModel::findOrFail($id);
    $action->delete();

    return redirect()->back()->with('message', 'Disciplinary action deleted successfully.');
}

}
