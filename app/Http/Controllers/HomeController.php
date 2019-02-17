<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "All Documents";
        $docus = Docu::orderBy('created_at', 'desc')->get();
        return view('home', compact('title', 'docus'));
    }

    public function accepted()
    {
        $title = 'Accepted Documents';
        $docus = Docu::withTrashed()
        ->orderBy('created_at' , 'desc')
        ->where('is_accepted', '1')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function inactive()
    {
        $title = "Inactive Documents";
        $docus = Docu::orderBy('created_at', 'desc')
        ->where('final_action_date', '<' , date('Y-m-d'))
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function received()
    {
        $title = 'Received Documents';
        $docus = Docu::join('forsendings', 'docus.id', '=', 'docu_id')
        ->where([
            ['forsendings.receipient_id', Auth::user()->id],
            // ['is_accepted', 0]
        ])
        ->orderBy('docus.created_at', 'desc')
        ->get();
        return view('home', compact('title', 'docus'));
    }

    public function archived()
    {
        $title = "Archived Documents";
        $docus = Docu::onlyTrashed()
        ->orderBy('created_at' , 'desc')
        ->get();
        return view('home', compact('title', 'docus'));
    }

}
