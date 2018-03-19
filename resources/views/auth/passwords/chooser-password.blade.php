@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')
	
	<div class="auth d-flex flex-column mt-5">
		
		<div class="d-f jc-sb ai-c ac-c mb-5">
			
			<a href="/" class="d-flex justify-content-center">
				<svg width="250" height="50">
					<use xlink:href="#aps-logo-full-white"></use>
				</svg>
			</a>
			
			<h1 class="d-flex mb-0 ml-3 pl-3 fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2">
				Choose Password
			</h1>
		</div>
		
		@if ($errors->any())
			<ul class="bgc-pink list-unstyled p-2">
				@foreach ($errors->all() as $error)
					<li class="c-white">{{ $error }}</li>
				@endforeach
			</ul>
		@endif
		
		<form method="POST" action="{{ route('socialite.register') }}"
		      class="d-flex justify-content-center align-items-center flex-column">
			{{ csrf_field() }}
			
			<input type="hidden" name="data" value="{{ $data }}">
			
			@if(!$name)
			<!--- Name --->
				<label for="name" class="fsz-xs tt-u c-white ls-12 mt-4">Name</label>
				<input type="text" value="{{ old('name') }}" name="name" placeholder="Name..."
				       class="input-transparent w-100 mw-90">
			@else
				<input type="hidden" name="name" value="{{ $name }}">
			@endif
			
			<input type="hidden" name="email" value="{{ $email }}">
			
			<!--- Password --->
			<label for="password" class="fsz-xs tt-u c-white ls-12 mt-4">Choose new Password</label>
			<input type="password" name="password" placeholder="New password..."
			       class="input-transparent w-100 mw-90">
			
			<!--- Confirm Password --->
			<label for="password_confirmation" class="fsz-xs tt-u c-white ls-12 mt-4">Confirm New Password</label>
			<input type="password" name="password_confirmation" placeholder="Confirm password..."
			       class="input-transparent w-100 mw-90">
			
			<div class="w-100 mw-90 mt-4 d-flex align-items-center justify-content-between">
				
				<button class="btn btn-primary fsz-sm tt-u ls-12 fw-600" type="submit">Choose</button>
				
				<div class="d-flex">
					<a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
					<span class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
					<a href="{{ route('register') }}" class="c-white fsz-sm tt-u ls-12 fw-600">Cancel</a>
				</div>
			
			</div>
		
		</form>
	
	</div>


@endsection