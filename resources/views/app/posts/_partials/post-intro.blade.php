<div class="col-lg-4 col-md-6 mb-5 post-intro">
	<div class="card" style="width: 100%; height: 100%; min-height: 100%">
		@php($imgNumber = array_random([1,2,3,4,5]))
		
		<img class="card-img-top" src="{{ featuredImage($post) }}" alt="{{ $post->title }}">
		
		<div class="card-body">
			
			<!--- Post Title --->
			<h4 class="card-title mb-2">
				<a href="{{ $post->path }}" class="text-dark">{{ $post->title }}</a>
			</h4>
			
			<!--- Post Time --->
			<div class="d-f jc-sb ai-c mt-2 mb-2 ai-c ac-c">
				<time datetime="2008-02-14 20:00" class="d-f ai-c ac-c">
					<vue-svg name="icon-calendar" square="16" classes="fill-gray"></vue-svg>
					<span class="ml-2 fsz-xs ls-11 tt-u c-gray">{{ $post->created_at->format('M d, Y') }}</span>
				</time>
			</div>
			
			<!--- Author  --->
			<a href="#" class="d-f fs-12 mt-1 mb-2 ac-c ai-c">
				<vue-svg name="icon-user" square="16" classes="fill-gray"></vue-svg>
				<span class="ml-1 fsz-xs ls-11 tt-u c-gray">{{ $post->author->name }}</span>
			</a>
			
			<!--- Taxonomy --->
			@if($post->category)
				<div class="d-f ai-c">
					<vue-svg name="icon-folder" square="16" classes="mr-1 fill-primary"></vue-svg>
					
					<a href="{{ route('archive.index', ['category', $post->category->slug ]) }}">
						{{ $post->category->name }}
					</a>
				
				</div>
			@endif
		
		<!--- Excerpt Or Short body --->
			<p class="card-text mb-3">
				{!! str_limit($post->excerpt, 130) !!}
			</p>
		
		</div>
		
		<div class="card-footer">
			<div class="d-f jc-sb">
				
				<!--- Comment Count --->
				<div class="d-f ai-c">
					{{ $post->comments_count }}
					<vue-svg name="icon-message-circle" square="14" classes="ml-1"></vue-svg>
				</div>
				
				<!--- Read more link --->
				<a href="{{ $post->path }}"
				   class="fsz-xs tt-u ls-11">
					<span>Read more</span>
					<vue-svg name="icon-chevrons-right" square="14" classes="fill-primary"></vue-svg>
				</a>
			</div>
		</div>
	</div>
</div>