<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">

    <!-- Styles -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">   

    <link rel="stylesheet" href="{{asset('css/materializev1.0.0/materialize.min.css')}}">

    <link href="{{asset('fonts/materialIcons.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="{{asset('js/jquery.js')}}"></script>

    <!-- DataTables -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>{{config('app.name')}}</title>
    
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    @if(Request::url() != 'http://dtracking.net/login')
    <script>
        var url = '{{URL::to('/')}}';
        window.Laravel = [$('meta[name="csrf-token"]').attr('content')];

        window.Laravel.userId = <?php echo auth()->user()->id; ?>
    </script>
    @endif
</head>

<body>
@if(Request::url() != 'http://dtracking.net/login')
    @include('inc.dropdowns')     
    @include('inc.sidenav')
@endif
    <div id="app">
        @if(Request::url() != 'http://dtracking.net/login')
            @include('inc.topnav')     
        @endif

        <div class="content" id="main">
            @yield('content')
        </div>
    </div>
    
    <script type="text/javascript" src="{{asset('js/materializev1.0.0/materialize.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    @stack('scripts')
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>