<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('layouts.common.head-meta')

    <!--- Styles --->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <!--- Javascript vars from php --->
    <script>{!! $javascripVars !!}</script>

</head>
<body>

<div id="app" class="app @yield('app-class')">
    @yield('content')
</div>

@stack('footer-scripts-prepend')
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@stack('footer-scripts-append')

@include('layouts.common.svg-sprite')

</body>
</html>