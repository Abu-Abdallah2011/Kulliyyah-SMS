<?php

namespace App\Http\Controllers;

use App\Models\sessions;
use Illuminate\Http\Request;
use App\Http\Requests\SessionsFormRequest;
use App\Http\Requests\SessionsFormController;

class SessionsController extends Controller
{
    // Show Edit Form
public function edit($id){
    $session = sessions::find($id);
    return view('EditSessions', compact('session'));
}

    // edit sessions
    public function update(SessionsFormRequest $request, $id)
    {
        $data = $request->validated();
        $sessions = sessions::where('id', $id)->update($data);
        return back()->with('message', 'Session/Term Updated Successfully!');
    }
}
