@extends('layouts.app')
<?php 
    $deadline_date = explode('-', $docu_to_edit->final_action_date);
    $deadline_month = $deadline_date[1];
    $deadline_year = $deadline_date[0];
    $deadline_day = $deadline_date[2];
?>
@section('content')
<div class="container">
  <div class="msg">
        @include('inc.message')
    </div>
  <div class="card white" style="z-index: 1">
    <div class="card-content black-text" >
      <a href='{{url("docu/{$docu_to_edit->id}")}}' class="btn red" style=" float:right;">Cancel</a>
      <br>
        <br>
          <div class="row">
            {!! Form::open(['action' => ['DocuController@update', $docu_to_edit->id], 'method' => 'POST']) !!}
            <div class="col s2">
              <div class="input-field">
                    {{Form::select('rushed', 
                        ['1' => 'Yes',
                        '0' => 'No'
                        ], $docu_to_edit->is_rush, ['id' => 'rushed']
                    )}}
                    
                
                <label for="rushed">
                  <b>Is it Rush? 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s3">
              <div class="input-field">
                    {{Form::select('location', 
                        ['Internal' => 'Internal',
                        'External' => 'External'
                        ], $docu_to_edit->location, ['id' => 'location']
                    )}}
                    
                
                <label for="location">
                  <b>Internal or External? 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s7">
              <div class="input-field">
                {{Form::select('typeOfDocu', $docu_type, $docu_to_edit->typeOfDocu, ['id' => 'typeOfDocu'])}}
                
                <label for="typeOfDocu">
                  <b>Type of Document 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s4">
              <div class="input-field">
                {{Form::select('confidential', 
                    ['1' => 'Yes',
                    '0' => 'No'
                    ], $docu_to_edit->confidentiality, ['id' => 'confidential']
                )}}
                <label for="confidential">
                  <b>Is it Confidential? 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s3">
              <div class="input-field">
                {{Form::select('complexity', 
                    ['Simple' => 'Simple',
                    'Complex' => 'Complex'
                    ], $docu_to_edit->complexity, ['id' => 'complexity']
                )}}
                
                <label for="complexity">
                  <b>Simple or Complex? 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s5">
              <div class="input-field">
                    {{Form::text('iso', $docu_to_edit->iso_code, [
                    'placeholder' => 'ISO Number'
                    ])}}
                    {{Form::label('iso', 'ISO Number')}}
                    </div>
            </div>
            <div class="col s12">
              <div class="input-field">
                    {{Form::text('subject', $docu_to_edit->subject, [
                    'placeholder' => 'Subject'
                    ])}}
                    
                
                <label for="subject">
                  <b>Subject 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                    {{Form::text('final_action_date', $docu_to_edit->final_action_date, [
                    'class' => 'datepicker'
                    ])}}
                
                <label for="final_action_date">
                  <b>Final Action Date
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Update', ['class'=>'btn btn-primary'])}}

                        {!! Form::close() !!}
                    </div>
            </div>
          </div>
          <!--End of div.row -->
        </div>
      </div>
    </div>
@stop

@push('scripts')
    <script>
    var holiday_list = [
      @foreach($holidays_list as $list)
        '{{$list->holiday_date}}',
      @endforeach
    ];
    function holidayDate(date){
      for(i = 0; i < holiday_list.length; i++){
        if(date.getMonth() == holiday_list[i].split("-")[0] - 1
            && date.getDate() == holiday_list[i].split("-")[1]){
              return [false, ,''];
            }
      }
      return [true, '', ''];
    }

    function noWeekendsOrHolidays(date){
      var noWeekend = $.datepicker.noWeekends(date);
      if(noWeekend[0]){
        return holidayDate(date);
      }
      else{
        return noWeekend;
      }
    }

    $('#rushed').change(function(){
          var ans = $(this).val();
          $(this).data('ans', ans)
          var dNow = new Date();
          var dExpected = new Date();
          if(ans==1){
            dExpected.setDate(dNow.getDate() + 2)
          }
          // else{
          //   dExpected.setDate(dNow.getDate() + 3)
          // }
          var buff = dayBuffer(dNow, dExpected);
          dExpected.setDate(dExpected.getDate() + buff);
          // $('#tempDate').text(dExpected);
          $('.datepicker').datepicker("option", "maxDate",
          new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate()))
      });

      $('#complexity').change(function(){
        var dNow = new Date();
        var dExpected = new Date();
        var ans = $('#rushed').val();
        if(ans != null && ans == 0){
          var compAns = $('#complexity').val();
          if(compAns == 'Simple'){
            dExpected.setDate(dNow.getDate() + 3)
          }
          else if(compAns == 'Complex'){
            dExpected.setDate(dNow.getDate() + 5)
          }
        }
        else if(ans == 1){
          dExpected.setDate(dNow.getDate() + 2)
        }
          var buff = dayBuffer(dNow, dExpected);
          dExpected.setDate(dExpected.getDate() + buff);
          $('.datepicker').datepicker("option", "maxDate",
          new Date(dExpected.getFullYear(), dExpected.getMonth(), dExpected.getDate()))
      });

      function dayBuffer(date1, date2){
        var buff = 0;
        //for weekends first
        while (date1 < date2) {
          var day = date1.getDay();
          // if ((day === 6) || (day === 0)) {
          //     buff = 2;
          // }
          if(day==5){
            buff += 2;
            date1.setDate(date1.getDate() + 3);
          }
          else if(day==6){
            buff += 1;
            date1.setDate(date1.getDate() + 2);
          }

          date1.setDate(date1.getDate() + 1);
        }

        //for holidays
        date1 = new Date();
        while(date1 < date2){
          for(i = 0; i < holiday_list.length; i++){
            if(date1.getMonth() == holiday_list[i].split("-")[0] - 1
              && date1.getDate() == holiday_list[i].split("-")[1]){
              buff += 1;
            }
          }
          date1.setDate(date1.getDate() + 1);
        }
        return buff;
      }

    $(document).ready(function(){
    $('select').formSelect();
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate : 0,
            beforeShowDay : noWeekendsOrHolidays,
            showAnim : "slideDown",
            maxDate : new Date({{$deadline_year}}, 
            {{$deadline_month - 1}}, {{$deadline_day}})
        });
    });
</script>
@endpush