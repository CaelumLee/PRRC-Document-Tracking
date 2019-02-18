@extends('layouts.app')

@section('content')
@include('inc.adminSideNav')
<div class="main">
    <div class="msg">
        @include('inc.message')  
    </div>
    @yield('main-content')
</div>

@endsection