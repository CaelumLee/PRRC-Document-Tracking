<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Docu extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    
    //Table Name
    protected $table = 'docus';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    protected $dates = ['deleted_at'];

   public function department()
   {
       return $this->belongsTo('App\Department');
   }

   public function typeOfDocu()
   {
       return $this->belongsTo('App\TypeOfDocu');
   }

   public function forsending()
   {
       return $this->hasOne('App\Forsending');
   }

   public function user()
   {
       return $this->belongsTo('App\User');
   }

   public function restore_docu($request)
   {
       $docu_to_restore = $this->onlyTrashed()->find($request->input('hidden_docuId'));
       $docu_to_restore->restore();
   }

   public function accept_docu($request)
   {
        $docu_to_approve = $this->find($request->input('hidden_docuId'));
        $docu_to_approve->is_accepted = 1;
        $docu_to_approve->save();
   }

   public function createDocu($data)
   {
        $ValidationExceptionExcel = new \App\ValidationExceptionExcel;
       if(is_object($data)){
            $dept_id = Auth::user()->department->id;
            $user_id = $data->input('user_id');
            $subj = $data->input('subject');
            $final_action_date = $data->input('final_action_date');
            $is_rush = $data->input('rushed');
            $iso = $data->input('iso');
            $location = $data->input('location');
            $docu_type = $data->input('typeOfDocu');
            $confidentiality = $data->input('confidential');
            $complexity = $data->input('complexity');
       }
       else if(is_array($data)){
            $dept_id = $data['department'];

            $user_id = $data['user_id'];

            if(!is_null($data['subject'])){
                $subj = $data['subject'];
            }
            else{
                $ValidationExceptionExcel->error('No Input for is it rushed', 
                "'Subject' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No Input for is it rushed' => ["'Subject' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }

            if(!is_null($data['final_action_date'])){
                $final_action_date = $data['final_action_date'];
            }
            else{
                $ValidationExceptionExcel->error('No Input for final action date', 
                "'Final Action Date' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No Input for is it rushed' => ["'Final Action Date' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }

            if(!is_null($data['rush'])){
                if(strtolower($data['rush']) == 'yes'){
                    $is_rush = 1;
                }
                else{
                    $is_rush = 0;
                }
            }
            else{
                $ValidationExceptionExcel->error('No Input for is it rushed', 
                "'Is it rushed' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No Input for is it rushed' => ["'Is it rushed' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }

            $iso = $data['iso'];

            if(!is_null($data['location'])){
                $location = ucfirst($data['location']);
            }
            else{
                $ValidationExceptionExcel->error('No Input for location', 
                "'Internal or External' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No Input for location' => ["'Internal or External' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }
            
            $docu_type_id_from_db = TypeOfDocu::where('docu_type', ucwords($data['typeOfDocu']))->value('id');
            if(!is_null($docu_type_id_from_db)){
                $docu_type = $docu_type_id_from_db;
            }
            else{
                $ValidationExceptionExcel->error('No Docu Type ID Found', 
                'No document type found for ' . $data['typeOfDocu']);
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No Docu Type ID Found' => ['No document type found for ' . $data['typeOfDocu']],
                //  ]);
                 throw $ValidationExceptionExcel;
            }
            
            if(!is_null($data['confidentiality'])){
                if(strtolower($data['confidentiality']) == 'yes'){
                    $confidentiality = 1;
                }
                else{
                    $confidentiality = 0;
                }
            }
            else{
                $ValidationExceptionExcel->error('No input for is it confidential', 
                "'Is It Confidential' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No input for is it confidential' => ["'Is It Confidential' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }
            
            if(!is_null($data['complexity'])){
                if(strtolower($data['complexity']) == 'simple'){
                    $complexity = 'Simple';
                }
                else if(strtolower($data['complexity']) == 'complex'){
                    $complexity = 'Complex';
                }
                else{
                    $ValidationExceptionExcel->error('Wrong input for simple or complex', 
                    "'Simple or Complex' column must be either 'Simple' or 'Complex' as inputted word");
                    // $error = \Illuminate\Validation\ValidationException::withMessages([
                    //     'Wrong input for simple or complex' => ["'Simple or Complex' column must be " .
                    //     "either 'Simple' or 'Complex' as inputted word"],
                    //  ]);
                     throw $ValidationExceptionExcel;
                }
            }
            else{
                $ValidationExceptionExcel->error('No input for simple or complex', 
                "'Simple or Complex' column must not be empty");
                // $error = \Illuminate\Validation\ValidationException::withMessages([
                //     'No input for simple or complex' => ["'Simple or Complex' column must not be empty"],
                //  ]);
                 throw $ValidationExceptionExcel;
            }
       }
        $docu_instace = new Docu;
        $thisYear = date('Y');
        $latestRefNum = $docu_instace->withTrashed()
        ->where('reference_number', 'like', $thisYear.'%')
        ->orderBy('id', 'desc')->pluck('reference_number')->first();
        if(is_null($latestRefNum)){
            $docu_instace->reference_number = $thisYear . '-0001';
        }
        else{
            $idWithLeadingZeroes = explode('-',$latestRefNum)[1];
            $incrementedId = (int)$idWithLeadingZeroes + 1;
            $newIdWithLeadingZeroes = $thisYear . '-' . str_pad($incrementedId, 4, '0', STR_PAD_LEFT);
            $docu_instace->reference_number = $newIdWithLeadingZeroes;
        }
        $docu_instace->department_id = $dept_id;
        $docu_instace->user_id = $user_id;
        $docu_instace->subject = $subj;
        $docu_instace->final_action_date = $final_action_date;
        $docu_instace->is_rush = $is_rush;
        $docu_instace->iso_code = $iso;
        $docu_instace->location = $location;
        $docu_instace->type_of_docu_id = $docu_type;
        $docu_instace->confidentiality = $confidentiality;
        $docu_instace->complexity = $complexity;
        $docu_instace->save();
        
        $outData = [
            'user' => (int)$docu_instace->user_id,
            'docu' => (int)$docu_instace->id,
            'ref_num' => $docu_instace->reference_number
        ];

        return $outData;
   }

   public function updateDocu($request, $docu_id)
   {
    $date_with_seconds = \Carbon\Carbon::parse($request->input('final_action_date') .' 17:0:0')->format('Y-m-d G:i:s');
        $docu_to_update = $this->whereId($docu_id)->first();
        $docu_to_update->subject = $request->input('subject');
        $docu_to_update->is_rush = $request->input('rushed');
        $docu_to_update->location = $request->input('location');
        $docu_to_update->iso_code = $request->input('iso');
        $docu_to_update->final_action_date = $date_with_seconds;
        $docu_to_update->save();
   }

   public function routeinfo()
   {
       return $this->hasMany('App\RouteInfo');
   }
}


    
       