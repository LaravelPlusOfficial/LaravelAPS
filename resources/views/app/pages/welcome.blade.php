@extends('layouts.app.master')

@section('header-class', 'p-ab t-0 w-100 z-fixed bd-n')

@section('main-nav-class', 'bg-opaque')

@section('content')
	
	<main class="main">
		
		<!-- Hero -->
		<section class="hero hero-welcome pt-5 pb-5"
		         style="background-image: url({{ setting('site_welcome_hero_background_image', mix('/site/defaults/welcome-hero-background.jpg')) }})">
			<div class="container-fluid mt-7">
				<div class="row">
					<div class="col-lg-12">
						<div class="hero-content bg-opaque">
							<h1 class="ls-12 ta-c">
								{!! setting('site_welcome_hero_title') !!}
							</h1>
							<p class="text-center fsz-xl mt-4 fw-300 ta-c">
								{!! setting('site_welcome_hero_sub_title') !!}
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		
		<!-- Posts -->
		<section class="posts bgc-gray-lighter">
			
			<div class="container-fluid pt-5 pb-5">
				
				<div class="row mt-5 mb-5">
					<div class="col-lg-12">
						<h2 class="pb-2 h4 ta-c tt-u ls-16 bdB">
							<vue-svg name="icon-pages" square="18"></vue-svg>
							Latest <span class="slim">Posts</span>
						</h2>
					</div>
				</div>
				
				<div class="row pt-4" v-match-heights="{el: ['.post-intro']}">
					
					@foreach($posts as $post)
						
						@include('app.posts._partials.post-intro', ['post' => $post])
					
					@endforeach
				
				</div>
			
			</div>
		
		</section>
	
	</main>

@endsection