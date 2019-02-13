<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Http\Request;
use Storage;
use File;

class RouteInfo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

     //Table Name
     protected $table = 'route_infos';
     //Primary Key
     public $primaryKey = 'id';

    public function docu()
    {
        return $this->belongsTo('App\Docu');
    }

    public function statuscode()
    {
        return $this->belongsTo('App\Statuscode');
    }

    public function file_store($file_to_upload)
    {
        $folder_name_with_current_date = Date('Ymd') . '_file_uploads';
        foreach($file_to_upload as $key => $upload){
        //get filename with extension
        $filenameWithExt = $upload->getClientOriginalName();
        //get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //get just extension
        $extension = $upload->getClientOriginalExtension();
        //filename to store
        $file = $filename. '_' . time(). '.' . $extension;
        //upload file
        $upload->storeAs('public/uploads/' . $folder_name_with_current_date, $file);
        $dataFile['file' . $key] = $file;
        }
        $file_info = [
            'path' => $folder_name_with_current_date,
            'dataFile' => $dataFile
        ];
        return $file_info;
    }

    public function createInfo($data, $docu_data)
    {   
        $routeInfo_instance = new Routeinfo;
        if(is_object($data)){
            if($docu_data != null){
                $user_id = $docu_data['user'];
                $docu_id = $docu_data['docu'];
            }
            else{
                $user_id = $data->input('editedBy');
                $docu_id = $data->input('hidden_docuId');
            }

            if($data->hasFile('filename')){
                $file_to_upload = $data->file('filename');
                $file_info = $routeInfo_instance->file_store($file_to_upload);
            }
            else{
                $file_info = [];
            }

            $edited_by = $user_id;
            $docu_id = $docu_id;
            $statuscode_id = $data->input('statuscode');
            $remarks = $data->input('remarks');
            $upload_data = json_encode($file_info);
        }

        else if(is_array($data)){
            $edited_by = $docu_data['user'];

            $docu_id = $docu_data['docu'];

            $status_id_from_db = Statuscode::where('status', ucwords($data['status']))->value('id');
            if(!is_null($status_id_from_db)){
                $statuscode_id = $status_id_from_db;
            }
            else{
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'No Status ID Found' => ['No status code found for ' . $data['status']],
                 ]);
                 throw $error;
            }

            if(!is_null($data['remarks'])){
                $remarks = $data['remarks'];
            }
            else{
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'Null Remark Found' => ['Remark must be filled out completely'],
                 ]);
                 throw $error;
            }

            $upload_data = json_encode([]);
        }

        $routeInfo_instance->edited_by = $edited_by;
        $routeInfo_instance->docu_id = $docu_id;
        $routeInfo_instance->statuscode_id = $statuscode_id;
        $routeInfo_instance->remarks = $remarks;
        $routeInfo_instance->upload_data = $upload_data;
        $routeInfo_instance->save();

        $outData = [
            'docu' => (int)$routeInfo_instance->docu_id,
            'routeinfo' => (int)$routeInfo_instance->id
        ];

        return $outData;
    }

    public function editInfo($request)
    {
        if($request->hasFile('filename')){
            $file_to_upload = $request->file('filename');
            $file_info =  $this->file_store($file_to_upload);
        }
        else{
            $file_info = [];
        }

        $info_to_update = $this->whereId($request->input('hidden_routeinfoID'))->first();
        $info_to_update->statuscode_id = $request->input('statuscode');
        $info_to_update->remarks = $request->input('remarks');
        $info_to_update->upload_data = json_encode($file_info);
        $info_to_update->save();
    }

    public function FileUploadToView($fileInfos)
    {
        $array_of_fileinfo = json_decode($fileInfos, true);
        if(empty($array_of_fileinfo)){
            $output = "<div class = 'row'><h4>No uploaded files found</h4></div>";
        }
        else{
            $output = "<div class = 'row'><h4>Uploaded Files</h4>";
            $directory_with_date_of_file = $array_of_fileinfo['path'];
            foreach($array_of_fileinfo['dataFile'] as $filename){
                switch(substr(strrchr($filename,'.'),1)){
                    case "png":
                    case "jpg":
                    case "jpeg":
                    case "bmp":
                        $output .= "<div class ='col s4'> <a href='http://dtracking.net/storage/uploads/" . 
                        $directory_with_date_of_file .  "/" . $filename . "' target='_blank'>" .
                        "<img id='image-upload' src='http://dtracking.net/storage/uploads/" . $directory_with_date_of_file . 
                        "/" . $filename . "'>" .
                        "</a><span>". $filename ."</span></div>";
                        break;
                    case "pdf":
                        $output .= "<div class ='col s4'> <a href='http://dtracking.net/storage/uploads/" . 
                        $directory_with_date_of_file . "/" . $filename . "' target='_blank'>" .
                        "<img id='image-upload' src='http://dtracking.net/images/pdf_logo.jpg'>" .
                        "</a></div>";
                        break;
                    case "pptx":
                        $output .= "<div class ='col s4'> <a href='http://dtracking.net/storage/uploads/" . 
                        $directory_with_date_of_file . "/" . $filename . "' download>" .
                        "<img id='image-upload' src='http://dtracking.net/images/powerpoint_logo.png'>" .
                        "</a><span>". $filename ."</span></div>";
                        break;
                    case "docx":
                        $output .= "<div class ='col s4'> <a href='http://dtracking.net/storage/uploads/" . 
                        $directory_with_date_of_file . "/" . $filename . "' download>" .
                        "<img id='image-upload' src='http://dtracking.net/images/word_logo.png'>" .
                        "</a><span>". $filename ."</span></div>";
                        break;
                }
            }
            $output .= '</div>';
        }
        return $output;
    }

}
