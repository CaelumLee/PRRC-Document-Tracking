<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Forsending;

class HistoryController extends Controller
{
    protected $forsending;

    public function __construct(Forsending $forsending)
    {
        $this->forsending = $forsending;
        $this->middleware('auth');
    }
    
    public function history($id)
    {
        $route_history = $this->forsending->whereDocu_id($id)->first()->audits;
        $docu_id = $id;
        // print_r($route_history);die();
        return view('docus.history', compact('route_history', 'docu_id'));
    }
}
