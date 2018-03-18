<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head itemscope itemtype="http://schema.org/Website">
@include('layouts.common.head-meta')

<!--- Styles --->
	@stack('prepend-head-styles')
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('/css/app.css') }}">
	@stack('head-styles')

<!--- Javascript vars from php --->
	<script>{!! $javascripVars !!}</script>
	
	
	@stack('head-scripts')
	{!! getGoogleAdsense() !!}
</head>
<body>

<div id="app" class="app p-rel">
	
	@include('layouts.app.off-canvas')
	
	@include('layouts.app.header')
	
	@yield('content')
	
	@include('layouts.app.footer')
	
	@include('layouts.common.flash')
	
	@include('layouts.common.logout-form')

</div>

@stack('footer-scripts-prepend')
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@stack('footer-scripts-append')

@include('layouts.common.svg-sprite')

</body>
</html>