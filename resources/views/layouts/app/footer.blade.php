<newsletter subscribe-url="{{ route('newsletter.subscribe') }}"></newsletter>

<footer class="footer pb-5 pt-5 bg-dark text-white">
	
	<section class="footer-wrap">
		
		<div class="container-fluid">
			
			<div class="row">
				
				<div class="col-lg-12">
					
					<p class="d-flex justify-content-center mt-5">
						
						<a href="https://twitter.com/LaravelPlus" class="mr-4">
							<vue-svg name="icon-twitter-colored" square="25"></vue-svg>
						</a>
						
						<a href="https://www.facebook.com/LaravelPlus/">
							<vue-svg name="icon-facebook-colored" square="25"></vue-svg>
						</a>
					
					</p>
					
					<p class="d-flex justify-content-center mt-4">
						
						@auth
							<a href="{{ route('admin.dashboard') }}"
							   class="fsz-sm tt-u ls-12 c-white mr-4">Dashboard</a>
							
							<a href=""
							   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
							   class="fsz-sm tt-u ls-12 c-white">Logout</a>
						@endauth
						
						@guest
							<a href="{{ url('/login') }}" class="fsz-sm tt-u ls-12 c-white mr-4">Login</a>
							<a href="{{ url('/register') }}" class="fsz-sm tt-u ls-12 c-white">Register</a>
						@endguest
					
					</p>
					
					<div class="d-f jc-c ai-xs-c fxd-xs-c">
						<p class="mb-2">
							<a href="{{ route('post.show', 'policy') }}" class="fsz-sm tt-u ls-12 mb-2 c-white mr-4">Privacy
								Policy</a>
						</p>
						<p class="mb-2">
							<a href="{{ route('post.show', 'terms') }}" class="fsz-sm tt-u ls-12 mb-2 c-white mr-4">Terms
								&amp;
								Conditions</a>
						</p>
						<p class="mb-2">
							<a href="{{ route('post.show', 'contact') }}" class="fsz-sm tt-u ls-12 mb-2 c-white">Contact
								Us</a>
						</p>
					</div>
					
					<p class="d-flex justify-content-center mt-5 mb-5">
						<vue-svg name="aps-logo-full-dark" width="300" height="60"></vue-svg>
					</p>
					
					<p class="text-center fs-12 txt-6 txt-up ls-12 mb-0 fw-300 c-gray">
						&copy; LaravelAPS 2018
					</p>
					<p class="text-center fs-12 txt-6 txt-up ls-12 mb-5 fw-300 c-gray">
						All rights reserved
					</p>
				
				</div>
			
			</div>
		
		</div>
	
	</section>

</footer>