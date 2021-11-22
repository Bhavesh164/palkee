<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} |
        @isset($title)
        {{ $title }}
        @endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{asset('resources/assets/images/fav_icon.png')}}" />
    <link href="{{asset('resources/admin_template/assets/css/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('resources/admin_template/assets/js/loader.js')}}"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="{{asset('resources/admin_template/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('resources/admin_template/assets/css/plugins.css?'.time())}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('resources/admin_template/plugins/font-icons/fontawesome/css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('resources/admin_template/plugins/font-icons/fontawesome/css/fontawesome.css')}}">

    <!-- END GLOBAL MANDATORY STYLES -->




    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{asset('resources/admin_template/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/assets/css/forms/switches.css')}}">
    <link href="{{asset('resources/admin_template/plugins/file-upload/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />

    <script src="{{asset('resources/admin_template/plugins/sweetalerts/promise-polyfill.js')}}"></script>
    <script src="{{asset('resources/admin_template/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <link href="{{asset('resources/admin_template/plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('resources/admin_template/plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('resources/admin_template/assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/plugins/select2/select2.min.css')}}">
    <link href="{{asset('resources/admin_template/assets/css/components/tabs-accordian/custom-tabs.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('resources/admin_template/plugins/flatpickr/flatpickr.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('resources/admin_template/plugins/flatpickr/custom-flatpickr.css')}}" rel="stylesheet" type="text/css">
    <!--  END CUSTOM STYLE FILE  -->

    @yield('page_header_css')

    <link rel="stylesheet" href="{{asset('resources/assets/css/custom.css?'.time())}}">



</head>