<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
@include('layouts.common.head-meta')

<!--- Styles --->
	@stack('prepend-styles')
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">
	<link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
@stack('append-styles')
<!--- Javascript vars from php --->
	<script>{!! $javascripVars !!}</script>

</head>
<body>
@include('layouts.common.svg-sprite')

<div id="admin" class="admin">
	
	@include('layouts.admin.off-canvas')
	
	@include('layouts.admin.topbar')
	
	<main class="main d-f mh-100 fxg-1" v-cloak>
		<section class="main-content pt-4 d-f fxd-c jc-sb mh-100 fxg-1">
			@yield('content')
			
			<footer class="admin-footer bgc-gray-lighter">
				<div class="container-fluid d-f jc-sb ai-c pt-3 fxd-xs-c">
					<div class="d-f pb-3">
						<p class="mb-0 c-gray fsz-xs tt-u ls-12">
							Copyright &copy;
							<a href="https://twitter.com/_gchauhan" class="text-dark" target="_blank">Gurinder Chauhan</a>
						</p>
					</div>
					<div class="d-f pb-3">
						<a href="https://twitter.com/LaravelPlus" target="_blank" class="d-f jc-sb ai-c">
							<vue-svg name="icon-twitter-colored" square="20"></vue-svg>
							<span class="ml-2 tt-u fsz-xs ls-12" style="color: #009CFA">Laravel Plus</span>
						</a>
					</div>
				</div>
			</footer>
		</section>
	</main>
	
	@include('layouts.common.flash')
	
	<vue-confirm></vue-confirm>
	
	<v-dialog/>

</div>


@stack('prepend-scripts')
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('/js/admin.js') }}"></script>
@stack('append-scripts')
</body>
</html>