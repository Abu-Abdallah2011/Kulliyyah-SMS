<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use App\Http\Requests\SessionsFormRequest;
use App\Http\Requests\SessionsFormController;

class SessionsController extends Controller
{
    //Show/Display Classes in Database
    public function show(){

    return view('Sessions.sessionsDatabase', ['sessions' => sessions::latest()
    // ->filter(request(['search']))
    ->paginate(10)]);
}
 
     //Display Classes Registration Form
   public function create() {
    return view('Sessions.sessionsForm');
}

//Store Class Registration Information

public function store(SessionsFormRequest $request){
    
    $data = $request->validated();

    $save = sessions::create($data);

    return redirect('/sessions_database')->with('message', 'Maa Shaa Allaah! Session/Term recorded Successfully!');
}

    // Show Edit Form
public function edit($id){
    $session = sessions::find($id);
    return view('Sessions.EditSessions', compact('session'));
}

    // edit sessions
    public function update(SessionsFormRequest $request, $id)
    {
        $data = $request->validated();
        $sessions = sessions::where('id', $id)->update($data);
        return redirect('/sessions_database')->with('message', 'Session/Term Updated Successfully!');
    }

    // Delete Class
public function delete($id) {
    $session = sessions::where('id', $id)->delete();
    return redirect('/sessions_database')->with('message', 'Maa Shaa Allaah! Session/Term Deleted Successfully!');
}
}
