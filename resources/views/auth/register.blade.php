@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')
	
	
	<div class="auth d-flex flex-column mt-5 mb-6 pb-6">
		
		<div class="d-flex justify-content-center align-items-center align-content-center mb-3">
			
			<a href="/" class="d-flex justify-content-center">
				<svg width="250" height="50">
					<use xlink:href="#aps-logo-full-white"></use>
				</svg>
			</a>
			
			<h1 class="d-f fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2 lh-2.5">
				Signup
			</h1>
		</div>
		
		@php($registerationDisabled  = setting('users_registeration_enabled') != 'enable')
		
		@if($registerationDisabled)
			<p class="ta-c fsz-md ls-11 bgc-white p-2 bdr-5">Registeration are closed for now</p>
		@endif
		
		@if ($errors->any())
			<ul class="bgc-pink list-unstyled p-2">
				@foreach ($errors->all() as $error)
					<li class="c-white">{{ $error }}</li>
				@endforeach
			</ul>
		@endif
		
		<form action="/register" method="POST"
		      class="">
		{{ csrf_field() }}
		
		<!--- Name --->
			<fieldset
					{{ $registerationDisabled ? 'disabled' : '' }}
					class="d-f jc-c ai-c mt-3 pb-5 ta-c">
				<label for="" class="fsz-xs tt-u c-white ls-12">Name</label>
				<input type="text"
				       name="name"
				       placeholder="Name..."
				       class="input-transparent w-100"
				       value="{{ old('name') }}">
				
				<!--- Email --->
				<label for="" class="fsz-xs tt-u c-white ls-12 mt-4">Email</label>
				<input type="email"
				       name="email"
				       placeholder="Email..."
				       class="input-transparent w-100"
				       value="{{ old('email') }}">
				
				<!--- Password --->
				<label for="" class="fsz-xs tt-u c-white ls-12 mt-4">Password</label>
				<input type="password"
				       name="password"
				       placeholder="Password..."
				       class="input-transparent w-100">
				
				<!--- Confirm Password --->
				<label for="" class="fsz-xs tt-u c-white ls-12 mt-4">Confirm Password</label>
				<input type="password"
				       name="password_confirmation"
				       placeholder="Confirm password..."
				       class="input-transparent w-100">
				
				<div class="w-100 mt-2 d-flex ai-c jc-sb fxd-xs-c">
					
					<button class="btn btn-primary fsz-sm tt-u ls-12 fw-600 mt-4" type="submit">Sign up</button>
					
					<div class="d-flex mt-4">
						<a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
						<span class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
						<a href="{{ route('password.request') }}" class="c-white fsz-sm tt-u ls-12 fw-600">Reset
							Password</a>
					</div>
				
				</div>
			
			</fieldset>
		
		</form>
		
		@if(!$registerationDisabled)
			
			<div class="mb-5 d-f jc-c">
				
				<div class="d-f fxd-c bg-opaque-light p-4 bdr-10 fxg-1">
					
					<p class="c-white fsz-xs tt-u ls-12 fw-600 ta-c">Sign up using Following</p>
					
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