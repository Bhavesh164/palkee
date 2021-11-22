<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>
        {{ config('app.name') }} |
        @isset($title)
            {{ $title }} 
        @endisset
    </title>
    <link rel="icon" type="image/x-icon" href="{{asset('resources/assets/images/fav_icon.png')}}"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('resources/admin_template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('resources/admin_template/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('resources/admin_template/assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('resources/admin_template/assets/css/authentication/form-2.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    @yield('page_header_css')
    <link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/assets/css/forms/theme-checkbox-radio.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('resources/admin_template/assets/css/forms/switches.css')}}">
</head>