<header class="topbar header bgc-gray-lighter  @yield('header-class', '')" v-cloak>
	<nav class="navbar navbar-expand-lg">
		<ul class="navbar-nav mr-auto navbar-gray d-flex flex-row align-items-center">
			<li class="nav-item hide-above-lg">
				<a class="nav-link d-f jc-c ai-c txt-up ls-15 bold p-0 mr-3"
				   href="/login">
					<vue-svg name="lp-logo-small-dark" :width="40" :height="40"></vue-svg>
				</a>
			</li>
			<li class="nav-item hide-above-lg">
				<a class="nav-link d-flex fill-primary-hover p-0 "
				   href="#"
				   @click.prevent="toggleOffCanvas()">
					<vue-svg name="icon-menu" :width="25" :height="25"></vue-svg>
				</a>
			</li>
		</ul>
		
		<!--- MENU --->
		<ul class="navbar-nav ml-auto navbar-gray">
			<li class="nav-item">
				<a class="nav-link d-f jc-c ai-c fill-gray c-gray c-primary-hv fill-primary-hv fsz-sm tt-u ls-12"
				   href="#"
				   title="Logout"
				   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
				>
					<vue-svg name="icon-logout" :width="24" :height="24"></vue-svg>
					{{--<span>Logout</span>--}}
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
				</form>
			</li>
		</ul>
	
	
	</nav>
</header>