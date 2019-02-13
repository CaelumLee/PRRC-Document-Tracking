<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docu;
use App\Forsending;
use App\RouteInfo;
use App\User;
use DB;
use PDF;

class DynamicPDFController extends Controller
{
    
    function get_docu_data($docu_id)
    {
     $docu_data = Docu::withTrashed()->find($docu_id);
     return $docu_data;
    }

 
    function pdf($docu_id) 
    {
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($this->convert_docu_data_to_html($docu_id));
    return $pdf->stream();
    }

    function convert_docu_data_to_html($docu_id)
    {
        $docu_data = $this->get_docu_data($docu_id);
        if($docu_data->is_rush){
            $checked = 'checked';
        }
        else{
            $checked = '';
        }
        $output = '<!DOCTYPE html>
        <html>
        <head>
            <title> Reference Number: '. $docu_data->reference_number .'</title>
        </head>
        <body>
            
        
            <style>
            .logo
            {
                float: left;
                display: block;
                padding-right: 10px;
                width: 100px;
            }
        
            .comfortaa
            {
                font-family: "Comfortaa";
            }
        
            .roboto
            {
                font-family: "Roboto";
            }
        
            .heading
            {
                width: 100%;
            }
            .rush
            {
                border: 2px solid #0d47a1;
                padding: 20px;
                text-align: center;
                width: 100px;
                margin-top: 15px;
                border-radius: 8px;
            }
        
            .blue-text
            {
                color: #0d47a1;
            }
        
            .blue
            {
                background: #0d47a1 !important;
                color: white;
            }
        
            .lighter-blue
            {
                background: #0e67c2;
            }
        
            {
                padding: 5px;
        
            }
        
            .slip
            {
                border: 2px solid #0d47a1;
                border-collapse: collapse;
                width: 100%;
            }
        
            .slip td
            {
                border: 2px solid #0d47a1;
                padding: 8px;
            }
        
            .slip th
            {
                border: 2px solid #0d47a1;
                background-color: #3283ce;
                text-align: left;
                padding: 8px;
                color: white;
                font-family: comfortaa;
        
            }
        
            b
            {
                font-family: Comfortaa;
                color: #0d47a1;
            }
        </style>
        <table class="heading">
            <tr>
                <td>
                    <img src="https://i.imgur.com/qNfnFn3.png" class="logo">
                    <h3 class="comfortaa blue-text">PASIG RIVER REHABILITATION COMMISION</h3>
                    <p class="roboto">DOCUMENT ROUTING SLIP</p></td>
                <td>
                    <div class="roboto rush">
                        <label>
                            <input type="checkbox"'. $checked .'>
                            <span class="checkmark"></span>
                            RUSH
                        </label>
                    </div>
                </td>
                <td style="float:right;">
                <img src="' . url("/storage/qrcodes/" . $docu_data->reference_number . ".png") 
                . '" class="logo">
                </td>
            </tr>
        </table>
        <br><br>
            <table class="roboto slip">
                <tr>
                    <td colspan="2" ><b>From:</b> <br>	&nbsp;	&nbsp;'.Forsending::whereDocu_id($docu_data->id)->first()->sender.'</td>
                    <td colspan="2"><b>Date Received:</b>
                        <br> 	&nbsp;	&nbsp;'. \Carbon\Carbon::parse($docu_data->created_at)->format('Y-m-d g:i:s A') .'</td>
                        <td colspan="2"><b>Reference Number:</b> <br>
                            &nbsp;	&nbsp;'.$docu_data->reference_number.'</td>
                        </tr>
        
                        <tr>
                            <td colspan="6"><b>Subject:</b>
                                <br>	&nbsp;	&nbsp; '.$docu_data->subject.'</td>
                            </tr>
        
                            <tr>
                                <th colspan="6" class="blue" style="padding: 10px; text-align: center !important;">ROUTING SLIP</th>
                            </tr>
        
                            <tr>
                                <th >FROM</th>
                                <th >TO</th>
                                <th >DATE</th>
                                <th >INSTRUCTIONS/REMARKS</th>
                                <th >DEADLINE</th>
                                <th >DATE COMPLIED</th>
                            </tr>';
                            foreach(Forsending::whereDocu_id($docu_data->id)->first()->audits as $value){
                                $output .= '<tr>
                                <td>'.$value->new_values['sender'].'</td>
                                <td>'.User::whereId($value->new_values['receipient_id'])->first()->username.'</td>
                                <td>'. \Carbon\Carbon::parse($value->updated_at)->format('Y-m-d g:i:s A') .'</td>';
                                if(array_key_exists('routeinfo_id', $value->new_values)){
                                    $output .= '<td>'.$remark = RouteInfo::whereId($value->new_values['routeinfo_id'])->pluck('remarks')->first()."</td>";
                                }
                                else{
                                    $output .= '<td>No specified remark</td>';
                                }
                                
                                if(array_key_exists('date_deadline', $value->new_values)){
                                    if(is_array($value->new_values['date_deadline'])){
                                        //this happens when you upload thru excel
                                        $date_deadline = $value->new_values['date_deadline']['date'];
                                    }
                                    else{
                                        $date_deadline = $value->new_values['date_deadline'];
                                    }
                                    $output .= '<td>'.\Carbon\Carbon::parse($date_deadline)->endOfDay()->format('Y-m-d g:i:s A').'</td>';
                                }
                                else{
                                    $date_deadline =  Forsending::whereDocu_id($docu_data->id)->first()->date_deadline;
                                    $date_deadline = \Carbon\Carbon::parse($date_deadline)->endOfDay()->format('Y-m-d g:i:s A');
                                    $output .= '<td>' . $date_deadline . '</td>';
                                }
                                
                                if(array_key_exists('routeinfo_id', $value->new_values)){
                                    $compiled = RouteInfo::whereId($value->new_values['routeinfo_id'])->pluck('updated_at')->first();
                                    $output .= '<td">' . \Carbon\Carbon::parse($compiled)->endOfDay()->format('Y-m-d g:i:s A') . '</td>';
                                }
                                else{
                                    $output .= '<td">No specified compiled date</td>';
                                }
                             }
                        $output .= '</table>'.
                        '<footer>ISO CODE: ' . $docu_data->iso_code .'</footer></body></html>';
        return $output;
    }
}
