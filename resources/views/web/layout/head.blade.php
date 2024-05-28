<head>
    <meta charset="utf-8" />
    <title>
        {{$websiteData->name}} - @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi Top up {{$websiteData->name}} - @yield('title')"
    name="description" />
    <meta content="Rendi Julianto" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{$websiteData->logo_website_url}}" />

    <!-- Theme Config Js -->
    <script src="{{asset('assets/js/config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app-indah.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.0.1/remixicon.min.css" integrity="sha512-dTsohxprpcruDm4sjU92K0/Gf1nTKVVskNHLOGMqxmokBSkfOAyCzYSB6+5Z9UlDafFRpy5xLhvpkOImeFbX6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Datatables css -->
    <link href="{{asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet"
            type="text/css" />
    <link rel="stylesheet" href="https://mottie.github.io/Keyboard/css/keyboard.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css">

    <style>
        /* css used to override the definitions above */
        div.ui-widget {
            font-size: 1.8em;
        }
        button.ui-keyboard-button {
            margin: 2px;
        }
    </style>
    @yield('style')
    @yield('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> 
    </script> 
</head>
