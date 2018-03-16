<div class="card">
	
	<div class="d-f fxd-c ac-c ai-c mt-4">
		
		@if($dashboardUser->avatar)
			<img src="{{ $dashboardUser->avatar }}" class="avatar avatar-img">
		@else
			<div class="d-f jc-c bgc-gray-light fill-gray m-2 avatar avatar-svg ac-c ai-c">
				<vue-svg name="icon-user" square="60"></vue-svg>
			</div>
		@endif
			
		<div class="card-body">
			@if($dashboardUser->social_links)
				
				<div class="d-f jc-c">
					
					@foreach($dashboardUser->social_links as $slug => $link)
						
						<a href="{{ $link }}" target="_blank" class="{{ $loop->last ? '' : 'mr-3' }}">
							
							@switch($slug)
								@case('facebook_url')
									<vue-svg name="icon-facebook-colored" square="20"></vue-svg>
								@break
								
								@case('twitter_username')
									<vue-svg name="icon-twitter-colored" square="20"></vue-svg>
								@break
								
								@case('github_url')
									<vue-svg name="icon-github-colored" square="20"></vue-svg>
								@break
								
								@case('google_plus_url')
									<vue-svg name="icon-google-plus-colored" square="20"></vue-svg>
								@break
								
								@case('linkedin_url')
								<vue-svg name="icon-linkedin-colored" square="20"></vue-svg>
								@break
								
							@endswitch
							
						</a>
					
					@endforeach
					{{--@if(isset($dashboardUser->social_links['facebook_url']))--}}
						{{--<a href="{{ $dashboardUser->social_links['facebook_url'] }}" class="mr-3">--}}
							{{--<vue-svg name="icon-facebook-colored" square="20"></vue-svg>--}}
						{{--</a>--}}
					{{--@endif--}}
					{{--@if(isset($dashboardUser->social_links['twitter_username']))--}}
						{{--<a href="{{ $dashboardUser->social_links['twitter_username'] }}" class="mr-3">--}}
							{{--<vue-svg name="icon-twitter-colored" square="20"></vue-svg>--}}
						{{--</a>--}}
					{{--@endif--}}
				</div>
			
			@endif
		</div>
			
		<div class="mb-4 ml-3 mr-3">
			<h5 class="card-title ta-c mb-0">{{ $dashboardUser->name }}</h5>
			<p class="card-text mb-0 ta-c">{{ $dashboardUser->introduction }}</p>
		</div>
		
		<div class="mb-4">
			<a href="{{ route('admin.profile.edit', $dashboardUser->id) }}" class="card-link fsz-sm tt-u ls-12 c-gray c-primary-hv fill-gray fill-primary-hv d-f ai-c">
				<vue-svg name="icon-edit" square="14" classes="mr-1"></vue-svg>
				Edit
			</a>
		</div>
		
	</div>
</div>