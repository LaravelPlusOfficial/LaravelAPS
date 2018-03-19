@extends('layouts.auth.master')

@section('app-class', 'grad-atlas ai-c')

@section('content')
	
	<div class="auth d-flex flex-column mt-5">
		
		<div class="d-flex jc-c ai-c ac-c mb-3">
			
			<a href="/" class="d-flex jc-c">
				<svg width="250" height="50">
					<use xlink:href="#aps-logo-full-white"></use>
				</svg>
			</a>
			
			<h1 class="d-f fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2">
				Reset Password
			</h1>
		</div>
		
		<form method="POST" action="{{ route('password.email') }}" class="d-flex jc-c ai-c fxd-c mt-3">
		{{ csrf_field() }}
		
			@if(session('status'))
				<p class="bgc-green w-100 ta-c p-2 bdr-5 c-white fsz-md ls-11">{{ session('status') }}</p>
			@endif
			
			<!--- Email --->
			<label for="" class="fsz-xs tt-u c-white ls-12">Email</label>
			<input type="email" name="email" placeholder="Email..." class="input-transparent w-100 mw-90">
			
			<div class="w-100 mw-90 mt-4 d-flex ai-c justify-content-between">
				
				<button class="btn btn-primary fsz-sm tt-u ls-12 fw-600" type="submit">Reset</button>
				
				<div class="d-flex">
					<a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
					<span class="text-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
					<a href="{{ route('register') }}" class="text-white fsz-sm tt-u ls-12 fw-600">Sign up</a>
				</div>
			
			</div>
		
		</form>
	
	</div>


@endsection