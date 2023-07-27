<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassesFormRequest;
use App\Models\classes;
use Illuminate\Http\Request;

class classesCrudController extends Controller
{
    //Show/Display Classes in Database
public function show(){

    return view('Classes.classes_database', ['classes' => classes::latest()
    ->filter(request(['search']))->paginate(10)]);
}
 
     //Display Classes Registration Form
   public function create() {
    return view('Classes.classesForm');
}
      

//Store Class Registration Information

 public function store(ClassesFormRequest $request){
    
    $data = $request->validated();

    $save = classes::create($data);

    return redirect()->route('classes.show', ['sets' => classes::latest()
    ->filter(request(['search']))->paginate(10)])->with('message', 'Maa Shaa Allaah! Class recorded Successfully!');
}

 // Show Edit Form
 public function edit($id){
    $class = classes::find($id);
    return view('Classes.classEdit', compact('class'));
}

// Update Class
public function update(ClassesFormRequest $request, $id){
        
    $data = $request->validated();

    $update = classes::where('id', $id)->update($data);

    return redirect()->route('classes.show')->with('message', 'Maa Shaa Allaah! Class Updated Successfully!');
}

// Delete Class
public function delete($id) {
    $class = classes::where('id', $id)->delete();
    return redirect()->route('classes.show')->with('message', 'Maa Shaa Allaah! Class Deleted Successfully!');
}

}