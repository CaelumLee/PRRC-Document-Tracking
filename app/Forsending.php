<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Forsending extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    //Table Name
    protected $table = 'forsendings';
    //Primary Key
    public $primaryKey = 'send_id';

    public function docu()
    {
        return $this->belongsTo('App\Docu');
    }

    public function newRecordonCreateDocu($data, $route_data)
    {   
        $ValidationExceptionExcel = new \App\ValidationExceptionExcel;
        if(is_object($data)){
            $sender = $data->input('sender');
            $receipient = $data->input('recipient');
            $receiver_id = User::where('username', $receipient)->pluck('id')->first();
            $date_deadline = $data->input('final_action_date');
            $addressee = $data->input('addressee');
            $sender_add = $data->input('sender_add');  
        }

        else if(is_array($data)){
            if(!is_null($data['sender'])){
                $sender = $data['sender'];
            }
            else{
                $ValidationExceptionExcel->error('Null sender', "'Sender' column must not be empty");
                throw $ValidationExceptionExcel;
            }
            if(!is_null($data['recipient'])){
                $receipient = $data['recipient'];
                if(is_null($receipient)){
                    $ValidationExceptionExcel->error('Null recipient', "'Recipient' column must not be empty");
                    throw $ValidationExceptionExcel;
                }
                else{
                    $receiver_id = User::where('username', $receipient)->pluck('id')->first();
                    if(is_null($receiver_id)){
                        $ValidationExceptionExcel->error('No Receiver ID Found', 'No user found for ' 
                        . $data['recipient']);
                        throw $ValidationExceptionExcel;
                    }
                }
            }

            if(!is_null($data['final_action_date'])){
                $date_deadline = $data['final_action_date'];
            }
            else{
                $ValidationExceptionExcel->error('No Input for final action date', 
                "'Final Action Date' column must not be empty");
                throw $ValidationExceptionExcel;
            }
            
            $addressee = $data['addressee'];
            $sender_add = $data['sender_address'];
        }

        $docu_id = $route_data['docu'];
        $routeinfo_id = $route_data['routeinfo'];

        $forsending_instance = new Forsending;
        $forsending_instance->docu_id = $docu_id;
        $forsending_instance->sender = $sender;
        $forsending_instance->receipient_id = $receiver_id;
        $forsending_instance->date_deadline = $date_deadline;
        $forsending_instance->addressee = $addressee;
        $forsending_instance->sender_add = $sender_add;
        $forsending_instance->routeinfo_id = $routeinfo_id;
        $forsending_instance->save();
    }
 
    public function updateForSendRecord($request)
    {        
        $record_to_update = $this->whereDocu_id($request->input('hidden_docuId'))->first();
        $record_to_update->sender = $request->input('hidden_sender');
        $recipient_id = User::where('username', $request->input('receiver'))->pluck('id')->first();
        if(is_null($recipient_id)){
            throw new \Exception('No username found for '. $request->input('receiver'));
        }
        $record_to_update->receipient_id = $recipient_id;
        $record_to_update->date_deadline = $request->input('deadline');
        $record_to_update->routeinfo_id = $request->input('hidden_routeinfoID');
        $record_to_update->save();

        $forsend_data = [
            'ref_num' => $request->input('hidden_ref_number'),
            'docu' => $request->input('hidden_docuId'),
            'sender' => $request->input('hidden_sender')
        ];
        
        return $forsend_data;
    }
}
