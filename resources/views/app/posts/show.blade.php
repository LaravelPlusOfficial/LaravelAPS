@extends('layouts.app.master')

@section('header-class', 'bdB')

@section('title', $post->title)

@section('content')
	
	<main class="main">
		
		<!-- Posts -->
		<section class="posts">
			
			<div class="container-fluid mt-5 mb-5 pt-4 pb-5">
				
				@php($imgNumber = array_random([1,2,3,4,5]))
				
				<div class="row">
					<div class="col-lg-12">
						<div class="card" style="width: 100%">
							
							<!-- Featured Image -->
							<img class="card-img-top" src="{{ featuredImage($post) }}" alt="{{ $post->title }}">
							
							<div class="card-body">
								
								<!-- Post Title -->
								<h1 class="card-title h2 mb-2 pl-4 pr-4">{{ $post->title }}</h1>
								
								
								<div class="d-flex align-items-center mb-4 pl-4 pr-4">
									
									<time datetime="{{ $post->created_at->format('Y-m-d h:m') }}"
									      class="fs-12 txt-up ls-14 d-flex align-items-center">
										<vue-svg name="icon-calendar" square="14"></vue-svg>
										<span class="ml-1">{{ $post->created_at->format('M d, Y') }}</span>
									</time>
									
									<div class="divider fs-12 txt-up ls-14 d-flex align-items-center ml-1 mr-1">/</div>
									
									
									<!-- Categories Taxonomy -->
									@if($post->category)
										<div class="d-f ai-c">
											<vue-svg name="icon-folder" square="16"
											         classes="mr-1 fill-primary"></vue-svg>
											
											<a href="{{ route('archive.index',['category', $post->category->slug] ) }}">
												{{ $post->category->name }}
											</a>
										
										</div>
									@endif
								
								</div>
								
								
								<div class="card-text mb-3 post-body-wrap pl-4 pr-4">
									
									@if($post->table_of_content)
										<div class="d-f jc-fe">
											<div class="post-toc d-inline-block bdr-5">
												<p class="label bdB pb-1 fsz-sm tt-u ls-11">Table of content</p>
												{!! $post->table_of_content !!}
											</div>
										</div>
									@endif
									
									{!! $post->body !!}
								
								
								</div>
								
								<!-- Tags Taxonomy -->
								@if($post->tags->count())
									<div class="d-f ai-c pl-4 pr-4 mt-4">
										<vue-svg name="icon-tag" square="16" classes="mr-1 fill-primary"></vue-svg>
										<ul class="list-unstyled d-f mb-0 p-0 fxw-w">
											@foreach($post->tags as $tag)
												<li class="">
													<a class="fsz-sm mr-2 c-primary bd bdr-5 pl-1 pr-1 pb-1 pt-1 lh-1"
													   href="{{ route('archive.index', ['tag', $tag->slug]) }}">
														{{ $tag->name }}
													</a>
												</li>
											@endforeach
										</ul>
									</div>
								@endif
							
							</div>
							
							<!-- Footer -->
							<div class="card-footer">
								
								<div class="d-flex mb-3 jc-sb ai-c fxd-xs-c">
									
									<div class="author d-f ai-c pl-4 pr-4 mt-3">
										
										@if($post->author->avatar)
											<img src="{{ $post->author->avatar }}" alt="{{ $post->author->name }}"
											     class="sq-50 round">
										@else
											<vue-svg name="icon-user" square="40" classes="fill-gray"></vue-svg>
										@endif
										
										
										<div class=" d-flex flex-column ml-3">
											
											<h4 class="mb-0 h6 ml-1 d-f ac-c ai-c fsz-sm tt-u ls-12">
												<span class="">{{ $post->author->name }}</span>
											</h4>
											
											<div class="d-flex mt-1">
												
												@if(isset($post->author->social_links['twitter_username']))
													
													@php($twitterUsername = $post->author->social_links['twitter_username'])
													
													<a href="https://twitter.com/{{ $twitterUsername }}"
													   target="_blank"
													   class="mr-3">
														<vue-svg name="icon-twitter-colored" square="16"></vue-svg>
													</a>
												
												@endif
												
												@if(isset($post->author->social_links['facebook_url']))
													
													@php($facebookUrl = $post->author->social_links['facebook_url'])
													
													<a href="{{ $facebookUrl }}" class="mr-3" target="_blank">
														<vue-svg name="icon-facebook-colored" square="16"></vue-svg>
													</a>
												@endif
												
												@if(isset($post->author->social_links['github_url']))
													
													@php($githubUrl = $post->author->social_links['github_url'])
													
													<a href="{{ $githubUrl }}" class="mr-3" target="_blank">
														<vue-svg name="icon-github-colored" square="16"></vue-svg>
													</a>
												@endif
												
												@if(isset($post->author->social_links['linkedin_url']))
													
													@php($linkedin = $post->author->social_links['linkedin_url'])
													
													<a href="{{ $linkedin }}" class="mr-3" target="_blank">
														<vue-svg name="icon-linkedin-colored" square="16"></vue-svg>
													</a>
												@endif
											
											</div>
										</div>
									
									</div>
									
									<div class="share mt-3">
										
										<h5 class="fsz-xs tt-u ls-11 ta-c">
											Share
											<vue-svg name="icon-share" square="10"></vue-svg>
										</h5>
										
										<social-share url="{{ urlencode(Request::fullUrl()) }}"
										              title="{{ isset($seoTitle) ? urlencode($seoTitle) : urlencode($post->title) }}"
										              twitter-username="{{ setting('twitter_site_username') }}"
														{{--hashtags="{{ urlencode('one,two') }}"--}}
										></social-share>
									
									</div>
								
								</div>
							</div>
						</div>
					</div>
				</div>
			
			</div>
		
		</section>
		
		@include('app.posts._partials.comments.comment-section')
		
		<section class="pagination bgc-gray-light">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-6 col-md-6">
						
						@if(isset($prevPost))
							<a href="{{ route('post.show', $prevPost->slug) }}"
							   class="d-f ai-c pt-4 pb-4 trans-eio jc-fs c-gray fill-gray fill-pink-hv c-pink-hv">
								<vue-svg name="icon-chevron-thin-left" square="40"></vue-svg>
								<h5 class="mb-0">{{ $prevPost->title }}</h5>
							</a>
						@endif
					
					</div>
					
					<div class="col-lg-6 col-md-6">
						@if(isset($nextPost))
							<a href="{{ route('post.show', $nextPost->slug) }}"
							   class="d-f ai-c pt-4 pb-4 trans-eio ta-r jc-fe c-gray fill-gray fill-pink-hv c-pink-hv">
								<h5 class="mb-0">{{ $nextPost->title }}</h5>
								<vue-svg name="icon-chevron-thin-right" square="40"></vue-svg>
							</a>
						@endif
					</div>
				</div>
			</div>
		</section>
	
	</main>

@endsection

@push('footer-scripts-append')
	<script src="{{ mix('js/run_prettify.js') }}"></script>
@endpush