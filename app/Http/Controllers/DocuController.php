<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\User;
use App\Forsending;
use App\RouteInfo;
use App\Statuscode;
use App\Holidays;
use App\TypeOfDocu;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use DB;
use Auth;
use App\Notifications\NewlyCreatedDocu;

class DocuController extends Controller
{
    protected $statuses;
    protected $user;
    protected $routeInfo;
    protected $docu;
    protected $forsending;
    protected $holidays;
    protected $type_of_docu;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Statuscode $statuses, User $user,
    routeInfo $routeInfo, Docu $docu, Forsending $forsending, Holidays $holidays, TypeOfDocu $type_of_docu)
    {
        $this->middleware('auth');
        $this->statuses = $statuses;
        $this->user = $user;
        $this->routeInfo = $routeInfo;
        $this->docu = $docu;
        $this->forsending = $forsending;
        $this->holidays = $holidays;
        $this->type = $type_of_docu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'My Documents';
        $docus = $this->docu->orderBy('created_at' , 'desc')->where('user_id', '=', Auth::user()->id)->get();
        return view('home', compact('docus', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_list = $this->statuses->pluck('status','id');
        $user_not_including_the_current_user = $this->user->whereNotIn('id', [Auth::user()->id])->pluck('username');
        $holidays_list = $this->holidays->all();
        $docu_type = $this->type->where('is_disabled', '0')->pluck('docu_type', 'id');
        return view('docus.create', compact('status_list', 'user_not_including_the_current_user', 
        'holidays_list', 'docu_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'rushed' => 'required',
            'location' => 'required',
            'subject' => 'required',
            'sender' => 'required',
            'recipient' => 'required',
            'complexity' => 'required',
            'confidential' => 'required',
            'statuscode' => 'required',
            'remarks' => 'required',
            'filename' => 'nullable|array',
            'filename.*' => 'nullable|mimes:jpeg,bmp,png,pptx,pdf,docx|max:50000',
            'final_action_date' => 'required',
        ]);
        DB::beginTransaction();
            try{
                $qrcode = new BaconQrCodeGenerator;
                $docu_data= $this->docu->createDocu($request);
                $route_data = $this->routeInfo->createInfo($request, $docu_data);
                $this->forsending->newRecordonCreateDocu($request, $route_data);
                $qrcode->size(100)
                ->encoding('UTF-8')
                ->format('png')
                ->generate($docu_data['ref_num'], '../public/storage/qrcodes/' . $docu_data['ref_num'] . '.png');
                $request->session()->flash('success', 'Document Created');
                $users = User::where('id', '!=', auth()->id())->get();
                foreach($users as $user){
                    $user->notify(new NewlyCreatedDocu($docu_data));
                }
                DB::commit();
            }
            catch(\Exception $e){
                DB::rollback();
                // $request->session()->flash('error', 'No user found for recipient : ' . $request->input('recipient'));
                // return back()->withInput();
                throw $e;
            }
        
        return redirect()->route('docu.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $docu_to_be_shown = $this->docu->withTrashed()->findOrFail($id);
        $userForSendList = $this->user->whereNotIn('id', [Auth::user()->id])->get();   
        $all_routeinfo_of_docu = $this->routeInfo->orderBy('created_at' , 'desc')->whereDocu_id($id)->paginate(5);
        $editInfoValues = $this->routeInfo->whereDocu_id($id)->latest('id')->first();
        $forsendValues = $this->forsending->whereDocu_id($id)->first();
        $docu_id = $id;
        $holidays_list = $this->holidays->pluck('holiday_date')->toArray();
        $status_list = $this->statuses->pluck('status','id');
        
        return view('docus.show', compact('docu_to_be_shown', 'userForSendList', 'docu_id', 'all_routeinfo_of_docu', 
        'editInfoValues', 'forsendValues', 'status_list', 'holidays_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $docu_to_edit = $this->docu->withTrashed()->where('id', $id)->first();
        $docu_type = $this->type->where('is_disabled', '0')->pluck('docu_type', 'id');
        $holidays_list = $this->holidays->all();
        return view('docus.edit', compact('docu_to_edit', 'docu_type', 'holidays_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'rushed' => 'required',
            'location' => 'required',
            'subject' => 'required',
            'final_action_date' => 'required',
        ]);

        $docu = new Docu;
        $docu->updateDocu($request, $id);
        $request->session()->flash('success', 'Document Updated');
        return redirect()->route("docu.show", ["id" => $id]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $docu = $this->docu->withTrashed()->whereId($id)->first();    
        if($docu->deleted_at == null){
            $docu->delete();
            $request->session()->flash('success', 'Record ' . $docu->reference_number . 
            ' has been archived');
        }        
        return redirect()->route("archived");
    }

    
    public function restore(Request $request)
    {
        $this->docu->restore_docu($request);

        $request->session()->flash('success', 'Document restored!');

        return redirect()->route("docu.show", ["id" => $request->input('hidden_docuId')]);
    }

    public function approve(Request $request)
    {
        $this->docu->accept_docu($request);

        $request->session()->flash('success', 'Record finalized and accepted!');

        return redirect()->route("docu.show", ["id" => $request->input('hidden_docuId')]);
    }
}
