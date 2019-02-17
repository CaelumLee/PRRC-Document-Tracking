@extends('layouts.app')

@section('content')
@include('inc.adminSideNav')
<div class="main">
    @yield('main-content')
</div>

@endsection