<?php

namespace App\Http\Controllers;

use App\Models\sets;
use Illuminate\Http\Request;
use App\Http\Requests\SetsFormRequest;

class setsController extends Controller
{
    

//Show/Display Sets in Database
public function show(){

    return view('Sets.sets_database', ['sets' => sets::latest()
    ->filter(request(['search']))->paginate(10)]);
}
 
     //Display Sets Registration Form
   public function create() {
    return view('Sets.setsForm');
}
      

//Store Sets Registration Information

 public function store(SetsFormRequest $request){
    
    $data = $request->validated();

    $save = sets::create($data);

    return redirect()->route('sets.show', ['sets' => sets::latest()
    ->filter(request(['search']))->paginate(10)])->with('message', 'Maa Shaa Allaah! Set recorded Successfully!');
}

 // Show Edit Form
 public function edit($id){
    $set = sets::find($id);
    return view('Sets.setsEdit', compact('set'));
}

// Update Set
public function update(SetsFormRequest $request, $id){
        
    $data = $request->validated();

    $update = sets::where('id', $id)->update($data);

    return redirect()->route('sets.show')->with('message', 'Maa Shaa Allaah! Set Updated Successfully!');
}

// Delete Set
public function delete($id) {
    $set = sets::where('id', $id)->delete();
    return redirect()->route('sets.show')->with('message', 'Maa Shaa Allaah! Set Deleted Successfully!');
}

}
