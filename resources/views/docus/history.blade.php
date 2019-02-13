<?php 
use Carbon\Carbon;
?>
@extends('layouts.app')

@section('content')
<h4>Routing History</h4>
<table class="bordered" id="users-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>FROM</th>
      <th>TO</th>
      <th>DATE SENT</th>
      <th>INSTRUCTIONS/REMARKS</th>
      <th>DEADLINE</th>
      <th>DATE COMPLIED</th>
    </tr>
  </thead>
  <tbody>
    <?php $key = 1;?>
        @foreach($route_history as $out)
            
    <tr>
      <td>{{$key}}</td>
      <!-- From column -->
      <td>{{$out->new_values['sender']}}</td>
      <!-- To column -->
      <td>{{App\User::whereId($out->new_values['receipient_id'])->first()->username}}</td>
      <!-- Date sent column -->
      <td>{{Carbon::parse($out->updated_at)->format('Y-m-d g:i:s A')}}</td>
      <!-- Remark column -->
            @if(array_key_exists('routeinfo_id', $out->new_values))
                
      <td>{{$remark = App\RouteInfo::whereId($out->new_values['routeinfo_id'])->pluck('remarks')->first()}}</td>
            @else
                
      <td>No specified remark</td>
            @endif

            @if(array_key_exists('date_deadline', $out->new_values))
                @if(is_array($out->new_values['date_deadline']))
                    <?php $date_deadline = $out->new_values['date_deadline']['date'];?>
                @else
                    <?php $date_deadline = $out->new_values['date_deadline'];?>
                @endif
                <td>{{Carbon::parse($date_deadline)->endOfDay()->format('Y-m-d g:i:s A')}}</td>
            @else
                <?php $date_deadline = App\Forsending::whereDocu_id($docu_id)->first()->date_deadline;
                $date_deadline = Carbon::parse($date_deadline)->format('Y-m-d g:i:s A'); ?>
                <td>{{$date_deadline}}</td>
            @endif

      <!-- Compiled column -->
            @if(array_key_exists('routeinfo_id', $out->new_values))
                
      <?php $compiled = App\RouteInfo::whereId($out->new_values['routeinfo_id'])->pluck('updated_at')->first();?>
      <td>{{Carbon::parse($compiled)->format('Y-m-d g:i:s A')}}</td>
            @else
                
      <td>No specified compiled date</td>
            @endif
            
    </tr>
    <?php $key++;?>
        @endforeach
    
  </tbody>
</table>

@stop

@push('scripts')

<script>
    $(document).ready(function(){
        $('#users-table').DataTable({
            pagingType: "simple",
            pageLength: 10,
            dom: '<div>pt',
            language:{
                paginate:{
                    previous: "<i class='material-icons'>chevron_left</i>",
                    next: "<i class='material-icons'>chevron_right</i>"
                }
            },
            order: []
        });
    });

  </script>
@endpush