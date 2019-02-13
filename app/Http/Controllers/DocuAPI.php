<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Docu as DocuResource;
use App\Docu;

class DocuAPI extends Controller
{
    public function index()
    {
        $docus = Docu::get();

        return DocuResource::collection($docus);
    }
}
