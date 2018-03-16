@extends('layouts.app.master')

@section('content')
	
	<main class="main">
		
		<!--- Title --->
		<section class="posts pb-5">
			
			<section class="bgc-gray-light pt-5 pb-5">
				
				<div class="container-fluid">
					<div class="row">
						
						<div class="col-lg-12">
							
							<h2 class="ta-c tt-u ls-12 h4">
								<vue-svg name="icon-file-text" square="18"></vue-svg>
								{{ isset($pageTitle) ? $pageTitle : 'Search' }}
							</h2>
							
							@if(isset($pageDescription))
								<p class="ta-c">{{ $pageDescription }}</p>
							@endif
						
						</div>
					</div>
				</div>
				
				<div class="container-fluid mt-4">
					<div class="row jc-c">
						<div class="col-lg-6" itemscope itemtype="http://schema.org/WebSite">
							
							<link itemprop="url" href="{{ url('/') }}"/>
							
							<form class="d-f"
							      method="GET"
							      action="{{ route('app.search') }}"
							      role="search"
							      itemprop="potentialAction"
							      itemscope
							      itemtype="http://schema.org/SearchAction">
								
								<meta itemprop="target" content="{{ route('app.search') }}?q={q}"/>
								
								<input class="form-control mr-2" type="search" name="q" itemprop="query-input"
								       placeholder="Type to search..." required/>
								
								<button type="submit" class="d-f bg-n bd-n fill-gray cur-p">
									<vue-svg name="icon-search" square="24"></vue-svg>
								</button>
								
							</form>
							
							@if(isset($term))
							
								<p class="mb-0 mt-4 ta-c tt-u ls-11">Search Results for: <span
										class="tt-n">{{ $term }}</span></p>
								
							@endif
						</div>
					</div>
				</div>
			
			</section>
		
		</section>
		
		<section class="results">
			
			<div class="container-fluid">
				
				<div class="row">
					
					<div class="col-lg-12">
						
						@if(isset($posts) && !empty($posts->items()))
							<ul class="list-unstyled mt-5" v-search-highlight data-keyword="{{ $term }}">
								@foreach($posts->items() as $post)
									<li class="media mb-4 pb-4 {{ !$loop->last ? 'bdB' : '' }}">
										
										@if($thumbnail = optional($post->featuredImage)->variations['thumbnail']['path'])
											<img class="mr-3 sq-100 round" src="{{ $thumbnail }}">
										@else
											<img class="mr-3 sq-100 round"
											     src="/images/defaults/post-image-thumbnail.jpeg">
										@endif
										
										<div class="media-body">
											
											<h5 class="mt-0 mb-1">
												<a href="{{ $post->path }}">{{ $post->title }}</a>
											</h5>
											
											@if($post->post_type != 'page')
												
												<div class="d-f ai-c c-gray fill-gray mt-2 mb-2">
													<vue-svg name="icon-folder" square="14"></vue-svg>
													<span class="ml-2 tt-u fsz-xs ls-12 d-f ac-c ai-c">
														{{ $post->category->name }}
													</span>
												</div>
												
												<p class="mb-2">{{ $post->excerpt }}</p>
												
												<div class="d-f ai-c c-gray fill-gray mb-2">
													<vue-svg name="icon-user" square="14"></vue-svg>
													<span class="ml-2 tt-u fsz-xs ls-12 d-f ac-c ai-c">{{ $post->author->name }}</span>
												</div>
												
												
												<ul class="d-f list-unstyled mt-2 c-gray fill-gray">
													<li class="mr-2">
														<vue-svg name="icon-tag" square="14"></vue-svg>
													</li>
													@foreach($post->tags as $tag)
														<li>{{ $tag->name }} {{ !$loop->last ? ', ' : '' }} &nbsp;</li>
													@endforeach
												</ul>
												
											@else
												
												<p class="mb-2">{{ $post->excerpt }}</p>
											
											@endif
										
										</div>
									
									</li>
								@endforeach
							</ul>
							
							<div class="d-f jc-c mb-5 pb-5">
								{{ $posts->appends(request()->query())->links() }}
							</div>
						@else
							<p class="mb-5 pb-5 ta-c mt-5 pt-2">No Results</p>
						@endif
					
					</div>
				
				</div>
			
			</div>
		
		</section>
	
	</main>

@endsection

@push('footer-scripts-prepend')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"
	        integrity="sha256-IdYuEFP3WJ/mNlzM18Y20Xgav3h5pgXYzl8fW4GnuPo=" crossorigin="anonymous"></script>
@endpush