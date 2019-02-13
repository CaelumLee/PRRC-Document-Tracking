<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{{asset('favicon.ico')}}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Compiled and minified MaterializeCSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Datatable v1.10.19 -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.jqueryui.min.css"> -->
    

    

    <!-- Datatable js v1.10.19 -->
    <!-- <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.jqueryui.min.js"></script> -->
    
    <!-- Jquery 3.x -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>{{config('app.name')}}</title>
    <script>
        var url = '{{URL::to('/')}}';
        window.Laravel = [$('meta[name="csrf-token"]').attr('content')];    
    </script>
    
</head>
<body>