@extends('layouts.app.master')


@section('content')
	
	<main class="main">
		
		@include('app.pages._partials._title', ['title' => 'Contact Us'])
		
		<div class="container-fluid mb-5">
			
			<div class="row jc-c">
				
				<div class="col-lg-7 p-5 bdr-5">
					
					<form action="{{ route('post.show', 'contact') }}" method="POST" class="mb-5 mt-5">
						{{ csrf_field() }}
						
						@if ($errors->any())
							<ul class="list-unstyled alert alert-danger">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						@endif
						
						<div class="form-group">
							<label class="fsz-sm ls-12 tt-u " for="name">Name</label>
							<input type="text"
							       name="name"
							       id="name"
							       placeholder="Name..."
							       class="form-control"
							       value="{{ old('name') ?? optional($user)->full_name }}">
						</div>
						
						<div class="form-group">
							<label class="fsz-sm ls-12 tt-u " for="email">Email</label>
							<input type="email"
							       name="email"
							       id="email"
							       placeholder="Email..."
							       class="form-control"
							       value="{{ old('email') ?? optional($user)->email }}">
						</div>
						
						<div class="form-group">
							<label class="fsz-sm ls-12 tt-u " for="inquiry">Inquiry</label>
							<textarea name="inquiry"
							          id="inquiry"
							          placeholder="How we can help you..."
							          rows="5"
							          class="form-control">{{ old('inquiry') }}</textarea>
						</div>
						
						<recaptcha :input="true"></recaptcha>
						
						<button class="btn btn-primary fsz-sm ls-12 tt-u mt-3" type="submit">Send</button>
					
					</form>
				
				</div>
			
			</div>
		
		</div>
	
	
	</main>

@endsection

@push('footer-scripts-prepend')
	
	<!--- Google Recaptcha --->
	<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoaded&render=explicit" async defer>
	</script>

@endpush