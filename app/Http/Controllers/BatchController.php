<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\Forsending;
use App\RouteInfo;
use DB;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use Auth;

class BatchController extends Controller
{
    protected $docu;
    protected $routeInfo;
    protected $forsending;

    public function __construct(Docu $docu, routeInfo $routeInfo, Forsending $forsending){
        $this->middleware('auth');
        $this->docu = $docu;
        $this->routeInfo = $routeInfo;
        $this->forsending = $forsending;
    }

    public function index()
    {
        $title = 'Batch Adding of Documents';
        return view('batchfile.batch', compact('title'));
    }

    public function add(Request $request)
    {
        if($request->hasFile('batch_upload')){
            if($request->file('batch_upload')->getClientOriginalExtension() == 'xlsx'){
                $file = $request->file('batch_upload')->getRealPath();       
                $data = \Excel::selectSheetsByIndex(0)->load($file, function($reader){
                })->get();
                if(!empty($data) && $data->count()){
                    $ref_num_holder = array();
                    DB::beginTransaction();
                    foreach($data as $row){
                        try{
                            $DataFromExcel = array();
                            $DataFromExcel['user_id'] = $request->input('user_id');
                            $DataFromExcel['rush'] = $row->is_it_rush;
                            $DataFromExcel['location'] = $row->interna_or_external;
                            $DataFromExcel['department'] = Auth::user()->department->id;
                            $DataFromExcel['confidentiality'] = $row->is_it_confidential;
                            $DataFromExcel['complexity'] = $row->simple_or_complex;
                            $DataFromExcel['typeOfDocu'] = $row->type_of_document;
                            $DataFromExcel['iso'] = $row->iso_number;
                            $DataFromExcel['subject'] = $row->subject;
                            $DataFromExcel['sender'] = $row->sender;
                            $DataFromExcel['sender_address'] = $row->sender_address;
                            $DataFromExcel['recipient'] = $row->recipient;
                            $DataFromExcel['addressee'] = $row->addressee;
                            $DataFromExcel['status'] = $row->status;
                            $DataFromExcel['remarks'] = $row->remarks;
                            $DataFromExcel['final_action_date'] = $row->final_action_date;
                            $docu_instance = $this->docu->createDocu($DataFromExcel);
                            $routing_info_instance = $this->routeInfo->createInfo($DataFromExcel, $docu_instance);
                            $this->forsending->newRecordonCreateDocu($DataFromExcel, $routing_info_instance);
                            array_push($ref_num_holder, $docu_instance['ref_num']);
                        }
                        catch(\Exception $e){
                            DB::rollback();
                            throw $e;
                        }
                    }
                    
                    $qrcode = new BaconQrCodeGenerator;
                    foreach($ref_num_holder as $ref_num){
                        $qrcode->size(100)
                        ->encoding('UTF-8')
                        ->format('png')
                        ->generate($ref_num, '../public/storage/qrcodes/' . $ref_num . '.png');
                    }

                    DB::commit();
                    $request->session()->flash('success', 'Documents Created');
                    return redirect('/docu');
                }
            }
            else{
                $request
                ->session()
                ->flash('error', 'File not an excel file! Download the copy and use it for 
                uploading multiple records');
                return back();
            }
        }
        else{
            $request
            ->session()
            ->flash('error', 'File not found! Upload the recommended excel file!');
            return back();
        }
    }
}
