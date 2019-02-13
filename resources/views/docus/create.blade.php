@extends('layouts.app')

@section('content')

<div class = "msg">
    @include('inc.message')
</div>
<div class ="container">
  <h4 class="grey-text text-darken-3" >Add a Record</h4>
  <h4 id="tempDate"></h4>
  <div class="card white">
    <div class="card-content black-text">
      <a href="/docu" class="btn red" style=" float:right; margin:auto;">Cancel</a>
      <br>
        <br>
          <div class="row">
            {!! Form::open(['action' => 'DocuController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    
            {{Form::hidden('user_id', Auth::user()->id)}}

            
            <div class="col s2">
              <div class="input-field">
                {{Form::select('rushed', 
                    ['1' => 'Yes',
                    '0' => 'No'
                    ], null, ['placeholder' => 'Yes/No','id' => 'rushed', 'data-ans']
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
                    ], null, ['placeholder' => 'Choose your option', 'id' => 'location']
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
                {{Form::select('typeOfDocu', $docu_type, null, ['placeholder' => 'Choose your option', 'id' => 'typeOfDocu'])}}
                
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
                    ], null, ['placeholder' => 'Yes/No','id' => 'confidential']
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
                    ], null, ['placeholder' => 'Choose your option', 'id' => 'complexity']
                )}}
                
                <label for="complexity">
                  <b>Simple or Complex? 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            
            <div class="col s4">
              <div class="input-field">
                {{Form::text('iso', '', ['placeholder' => 'ISO Number'])}}
                {{Form::label('iso', 'ISO Number')}}
                </div>
            </div>
            <div class="col s12">
              <div class="input-field">
                {{Form::text('subject', '', ['placeholder' => 'Subject'])}}
                
                <label for="subject">
                  <b>Subject 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                {{Form::text('sender', '', ['placeholder' => 'Sender', 'class' => 'autocomplete', 'id' => 'sender', 'autocomplete' => 'off'])}}
                
                <label for="sender">
                  <b>Sender 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                {{Form::text('recipient', '', ['placeholder' => 'Recipient', 'class' => 'autocomplete', 'id' => 'recipient', 'autocomplete' => 'off'])}}
                
                <label for="recipient">
                  <b>Recipient 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                {{Form::text('sender_add', '', ['placeholder' => 'Sender Address'])}}
            {{Form::label('sender_add', 'Sender Address')}}
                </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                {{Form::text('addressee', '', ['placeholder' => 'Addressee'])}}
            {{Form::label('addressee', 'Addressee(s)')}}
                </div>
            </div>
            <div class="col s6">
              <div class="input-field">
                {{Form::select('statuscode', $status_list, null, ['placeholder' => 'Choose your option', 'id' => 'statuscode'])}}
            
                <label for="statuscode">
                  <b>Status 
                    <span style="color:red">*</span>
                  </b>
                </label>
              </div>
            </div>
            <div class="col s6">
              <div class="file-field input-field">
                <div class="btn">
                  <span>File</span>
                  <input type="file" name="filename[]" multiple>
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" type="text" placeholder="Upload one or more files">
                </div>
              </div>
            </div>
              <div class="col s12">
                <div class="input-field">
                {{Form::text('remarks', '', ['placeholder' => 'Remarks'])}}
                
                  <label for="remarks">
                    <b>Remarks 
                      <span style="color:red">*</span>
                    </b>
                  </label>
                </div>
              </div>
              <div class="col s4">
                <div class="input-field">
                {{Form::text('final_action_date', '', ['class' => 'datepicker', 'autocomplete' => 'off'])}}
                
                  <label for="final_action_date">
                    <b>Final Action Date 
                      <span style="color:red">*</span>
                    </b>
                  </label>
                </div>
              </div>
              <div class="col s8 ">
                <div class="input-field right-align">
                {{Form::submit('Create', ['class'=>'btn green'])}}            
                {!! Form::close() !!} 
                </div>
              </div>
            </div>
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
        $('#statuscode option:first').attr('disabled', true);
        $('#location option:first').attr('disabled', true);
        $('#complexity option:first').attr('disabled', true);
        $('#typeOfDocu option:first').attr('disabled', true);
        $('#rushed option:first').attr('disabled', true);
        $('#confidential option:first').attr('disabled', true);
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            minDate : 1,
            beforeShowDay : noWeekendsOrHolidays,
            showAnim : 'slideDown'
        });
        $('select').formSelect();
        $('input.autocomplete').autocomplete({
            data: {
              "Apple": null,
              "Microsoft": null,
              "Google": 'https://placehold.it/250x250'
            },
            limit : 5,
            sortFunction : function(a, b , inputString){
                return a.indexOf(inputString) - b.indexOf(inputString);
            }
        });
    });
</script>
@endpush