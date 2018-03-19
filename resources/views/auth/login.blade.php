@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')
	
	<div class="auth d-flex flex-column mt-5">
		
		<div class="d-flex justify-content-center align-items-center align-content-center mb-3">
			
			<a href="/" class="d-flex justify-content-center">
				<svg width="250" height="50">
					<use xlink:href="#aps-logo-full-white"></use>
				</svg>
			</a>
			
			<h1 class="d-f fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2 lh-2.5">
				Login
			</h1>
		</div>
		
		@php($registerationDisabled  = setting('users_registeration_enabled') != 'enable')
		
		@php($action = request()->exists('backUrl') ? route('login', ['backUrl' => request()->get('backUrl')] ) : route('login'))
		
		@if ($errors->any())
			<ul class="bgc-pink list-unstyled p-2">
				@foreach ($errors->all() as $error)
					<li class="c-white">{{ $error }}</li>
				@endforeach
			</ul>
		@endif
		
		<form action="{{ $action }}" method="POST"
		      class="d-f jc-c ai-c flex-column mt-3 pb-5">
		{{ csrf_field() }}
		
		<!--- Email --->
			<label for="" class="fsz-xs tt-u c-white ls-12">Email</label>
			<input type="text" name="email" placeholder="Email..." class="input-transparent fsz-def fw-300 w-100 mw-90"
			       value="{{ old('email') }}">
			
			<!--- Password --->
			<label for="password" class="fsz-xs tt-u c-white ls-12 mt-4">Password</label>
			<input type="password" name="password" placeholder="Password..." class="input-transparent w-100 mw-90">
			
			<div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
				<label for="remember" class="c-white fsz-sm ls-11 tt-u">
					<input type="checkbox" name="remember" id="remember">
					<span>Remember me</span>
				</label>
			</div>
			
			<div class="w-100 mw-90 d-flex ai-c jc-sb fxd-xs-c">
				
				<button class="btn btn-primary fsz-sm tt-u ls-12 fw-600 mt-4" type="submit">Login</button>
				
				<div class="d-flex mt-4">
					<a href="{{ route('password.request') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Reset
						password</a>
					<span class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
					<a href="{{ route('register') }}" class="c-white fsz-sm tt-u ls-12 fw-600">Sign up</a>
				</div>
			
			</div>
		
		</form>
		
		@if(!$registerationDisabled)
			
			<div class="mb-5 d-f jc-c">
				
				<div class="d-f fxd-c bg-opaque-light p-4 bdr-10 mw-90 fxg-1">
					
					<p class="c-white fsz-xs tt-u ls-12 fw-600 ta-c">Login using Following</p>
					
					<div class="d-flex justify-content-center mt-1">
						<a href="{{ route('socialite.to.provider', 'google') }}" title="google" class="mr-4">
							<vue-svg name="icon-google-colored" square="30"></vue-svg>
						</a>
						
						<a href="{{ route('socialite.to.provider', 'facebook') }}" title="facebook" class="mr-4">
							<vue-svg name="icon-facebook-colored" square="30"></vue-svg>
						</a>
						
						<a href="{{ route('socialite.to.provider', 'twitter') }}" title="twitter" class="mr-4">
							<vue-svg name="icon-twitter-colored" square="30"></vue-svg>
						</a>
						
						<a href="{{ route('socialite.to.provider', 'github') }}" title="github">
							<vue-svg name="icon-github-colored" square="30"></vue-svg>
						</a>
						
					</div>
				
				</div>
			</div>
		
		@endif
	
	</div>


@endsection