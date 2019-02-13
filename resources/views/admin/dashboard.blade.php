@extends('layouts.app')

@section('content')
@include('inc.snav')
<div class="main">

<div id="chart">
    
</div>
    
</div>

@stop

@push('scripts')
<script src="{{asset('js/dashboard.js')}}"></script>
@endpush