<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{asset('uploads/'.$all_configs['site_favicon'])}}" sizes="16x16">
    <meta name="site-url" content="{{ url('/') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ULE Visualizer</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/material-icons/material-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/material-vendors.min.css') }}">
    <link href="{{ asset('css/admin-panel.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <style>
        body .content .content-wrapper {
            height: calc(100vh - 175px);
            font-family: 'Montserrat', sans-serif;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .custom-control-label{cursor: pointer;}
        .ap-op-link {padding:10px !important;}
        .ap-op-link.collapsed{margin-bottom: 10px; border-bottom: 1px solid #673ab7!important;}
        .ap-op-link .card-title{font-size: 0.95rem;}
        .ap-op-content{border-width: 0 1px 1px;}
    </style>
</head>
<body class="vertical-layout vertical-compact-menu material-vertical-layout material-layout 2-columns  fixed-navbar {{ in_array($menu, ['models', 'media', 'pages'])?'todo-application':'' }}" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">
    @include('elements.header')
    @include('elements.menu')
    <div class="app-content content" id="app">
        @yield('content')
    </div>
    @include('elements.footer')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    @stack('scripts')
</body>
</html>
