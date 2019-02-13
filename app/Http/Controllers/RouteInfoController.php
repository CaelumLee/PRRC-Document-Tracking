<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RouteInfo;

class RouteInfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newRouteInfo(Request $request)
    {
        $this->validate($request, [
            'remarks' => 'required',
            'statuscode' => 'required',
            'filename' => 'nullable|array',
            'filename.*' => 'nullable|mimes:jpeg,bmp,png,pptx,pdf,docx|max:50000',
        ]);
        $info = new routeInfo();
        $info->createInfo($request, null);
        
        $request->session()->flash('success', 'Route info added!');

        return redirect()->route('docu.show', ['id' => $request->input('hidden_docuId')]);
    }

    public function editRouteInfo(Request $request)
    {
        $this->validate($request, [
            'remarks' => 'required',
            'statuscode' => 'required',
            'filename' => 'nullable|array',
            'filename.*' => 'nullable|mimes:jpeg,bmp,png,pptx,pdf,docx|max:50000',
        ]);
        $info = new routeInfo();
        $info->editInfo($request);
        
        $request->session()->flash('success', 'Route info edited!');

        return redirect()->route('docu.show', ['id' => $request->input('hidden_docuId')]);
    }
}
