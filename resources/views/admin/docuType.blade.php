@extends('admin.dashboard')
@section('main-content')
<div class="row">
    <h4>Document Types</h4>
    <div class="col s12">
        <table class="dashboard-table" id="docu-type-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Type Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docu_type_list as $type)
                    @if($type->is_disabled == 1)
                        <tr class = "grey darken-1">>
                    @else
                        <tr>
                    @endif
                            <td>{{$type->id}}</td>
                            <td>{{$type->docu_type}}</td>
                            <td>
                                <a href='#edit' class='waves-effect white waves-green btn-small btn-flat modal-trigger edit' data-id = "{{$type->id}}" data-name = "{{$type->docu_type}}"><i class='material-icons'>edit</i></a>
                                <a href='#delete' class='waves-effect white waves-red btn-small btn-flat modal-trigger delete' data-id = "{{$type->id}}" data-name = "{{$type->docu_type}}" data-is_disabled = "{{$type->is_disabled}}"><i class='material-icons'>delete</i></a>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="edit" class="modal">
    <div id="edit_modal_placeholder" class="modal-content">
        <div class="row">
            {!!Form::open(['action' => ['DocuTypeDashboardController@edit'], 'method' => 'POST'])!!}
            <h4>Edit values</h4>
            <div class="input-field col s12">
                <input disabled value="" id="disabled" type="text" class="validate">
                <!-- <label for="disabled">Current docu type value</label> -->
            </div>
            <div class="col s12 input-field">
                {{Form::text('docu_type', '', ['id' => 'docu_type'])}}
                {{Form::label('docu_type', 'Enter new value for document type')}}
            </div>
            <input type="hidden" id="docutype_id" name = "docutype_id" value = "">
        </div>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Send', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>

<div id="delete" class="modal">
    <div id="delete_modal_placeholder" class="modal-content">
        <div class="row">
            <h4 id="title-placeholder">...</h4>
            {!!Form::open(['action' => ['DocuTypeDashboardController@disable'], 'method' => 'POST'])!!}
            <p>Are you sure you want to chuchu this document?</p>
            <input type="hidden" id="docutype_id_disable" name = "docutype_id_disable" value = "">
        </div>
    </div>
    <div class="modal-footer">
    <a href="#!" class="modal-close waves-effect waves-red btn red">No</a>
      {{Form::submit('Yes', ['class' => 'btn green'])}}
        {!!Form::close()!!}
    </div>
</div>
@stop

@push('scripts')
<script src="{{asset('js/docuType.js')}}"></script>
@endpush
