<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forsending;
use App\User;
use App\Notifications\SendToNotif;

class ForsendController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendTo(Request $request)
    {
        $this->validate($request, [
            'receiver' => 'required',
            'deadline' => 'required',
        ]);
        $sendTo = new Forsending();
        try{
            $forsend_data = $sendTo->updateForSendRecord($request);
            $user = User::where('username', $request->input('receiver'))->first();
            $user->notify(new SendToNotif($forsend_data));
        }
        catch(\Exception $e){
            $request->session()->flash('error', 'No user found for recipient : ' . $request->input('receiver'));
            return back();
            // throw $e;
        }

        $request->session()->flash('success', 'Record sent to ' . $user->username);
        return redirect()->route("docu.index");
    }
}
