@extends('layouts.app.master')

@section('content')
	
	<main class="main">
		
		<!--- Title --->
		<section class="posts">
			
			<section class="pt-5 pb-5 grad-azure">
				
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-lg-12 c-white fill-white">
							<h2 class="ta-c tt-u ls-12 h4">
								<vue-svg name="icon-file-text" square="18"></vue-svg>
								{{ isset($pageTitle) ? $pageTitle : 'Posts' }}
							</h2>
							
							@if(isset($pageDescription))
								<p class="ta-c mb-0">{{ $pageDescription }}</p>
							@endif
						
						</div>
					</div>
				</div>
			
			</section>
			
			<section class="bgc-gray-lighter">
				
				<div class="container-fluid pt-5 pb-5">
					
					<div class="row pt-5 pb-5" v-match-heights="{el: ['.post-intro']}">
						
						@foreach($posts->items() as $post)
							
							@include('app.posts._partials.post-intro', ['post' => $post])
						
						@endforeach
					
					</div>
					
					@if($posts->previousPageUrl() || $posts->nextPageUrl() )
						<div class="row">
							
							<div class="col-lg-12 d-flex justify-content-between">
								
								<a href="{{ $posts->previousPageUrl() }}"
								   class="btn btn-primary btn-sm d-f ai-c fill-white tt-u fsz-sm ls-12 {{ ! $posts->previousPageUrl() ? 'disabled' : '' }}">
									<vue-svg name="icon-chevron-thin-left" square="14"></vue-svg>
									<span class="ml-2">Newer Posts</span>
								</a>
								<a href="{{ $posts->nextPageUrl() }}"
								   class="btn btn-primary btn-sm d-f ai-c fill-white tt-u fsz-sm ls-12 {{ ! $posts->nextPageUrl() ? 'disabled' : '' }}">
									<span class="mr-2">Older Posts</span>
									<vue-svg name="icon-chevron-thin-right" square="14"></vue-svg>
								</a>
							
							</div>
						
						</div>
					@endif
				
				</div>
			</section>
		
		</section>
	
	</main>

@endsection