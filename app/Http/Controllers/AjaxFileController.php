<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RouteInfo;
use Auth;

class AjaxFileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getJsonFile(Request $request){
        
        $routeinfo_id = $request->input('dataID');
        $get_JsonFile_from_routeInfo = RouteInfo::find($routeinfo_id)->upload_data;
        $routeinfoModel = new RouteInfo;
        $html_to_display = $routeinfoModel->FileUploadToView($get_JsonFile_from_routeInfo);
        return response()->json([
            'File_Uploads' => $html_to_display
        ],200);
    }
}
