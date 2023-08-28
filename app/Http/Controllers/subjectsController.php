<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subjectsModel;
use App\Http\Requests\subjectsFormRequest;

class subjectsController extends Controller
{
    
    //Show/Display Subjects in Database
public function show(){

    return view('Subjects.subjects_database', ['subjects' => subjectsModel::latest()
    ->filter(request(['search']))->paginate(10)]);
}
 
     //Display Subjects Registration Form
   public function create() {
    return view('Subjects.subjectsForm');
}
      

//Store Subjects Registration Information

 public function store(subjectsFormRequest $request){
    
    $data = $request->validated();

    $save = subjectsModel::create($data);

    return redirect()->route('subjects.show', ['subjects' => subjectsModel::latest()
    ->filter(request(['search']))->paginate(10)])->with('message', 'Maa Shaa Allaah! Subject recorded Successfully!');
}

 // Show Edit Form
 public function edit($id){
    $subject = subjectsModel::find($id);
    return view('Subjects.subjectsEdit', compact('subject'));
}

// Update Subject
public function update(subjectsFormRequest $request, $id){
        
    $data = $request->validated();

    $update = subjectsModel::where('id', $id)->update($data);

    return redirect()->route('subjects.show')->with('message', 'Maa Shaa Allaah! Subject Updated Successfully!');
}

// Delete Subject
public function delete($id) {
    $subject = subjectsModel::where('id', $id)->delete();
    return redirect()->route('subjects.show')->with('message', 'Maa Shaa Allaah! Subject Deleted Successfully!');
}
}
