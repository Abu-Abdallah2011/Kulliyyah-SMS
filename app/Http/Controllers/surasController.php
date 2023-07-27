<?php

namespace App\Http\Controllers;

use App\Models\surasModel;
use Illuminate\Http\Request;
use App\Http\Requests\surasFormRequest;

class surasController extends Controller
{
    //Show/Display Surahs in Database
public function show(){

    return view('Surah.suras_database', ['suras' => surasModel::latest()
    ->filter(request(['search']))->paginate(10)]);
}
 
     //Display Surah Registration Form
   public function create() {
    return view('Surah.surasForm');
}
      

//Store Surah Registration Information

 public function store(surasFormRequest $request){
    
    $data = $request->validated();

    $save = surasModel::create($data);

    return redirect()->route('suras.show', ['suras' => surasModel::latest()
    ->filter(request(['search']))->paginate(10)])->with('message', 'Maa Shaa Allaah! Surah recorded Successfully!');
}

 // Show Edit Form
 public function edit($id){
    $sura = surasModel::find($id);
    return view('Surah.suraEdit', compact('sura'));
}

// Update Surah
public function update(surasFormRequest $request, $id){
        
    $data = $request->validated();

    $update = surasModel::where('id', $id)->update($data);

    return redirect()->route('suras.show')->with('message', 'Maa Shaa Allaah! Surah Updated Successfully!');
}

// Delete Surah
public function delete($id) {
    $class = surasModel::where('id', $id)->delete();
    return redirect()->route('suras.show')->with('message', 'Maa Shaa Allaah! Surah Deleted Successfully!');
}

}
