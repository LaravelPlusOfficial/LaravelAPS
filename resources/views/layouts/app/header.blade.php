<header class="header bdB @yield('header-class')">
	
	<nav class="navbar navbar-expand-lg @yield('main-nav-class', '')">
		
		<div class="container-fluid">
			
			<!--- LOGO --->
			<a class="navbar-brand d-flex" href="/">
				<svg width="220" height="30" class="d-none d-lg-block d-xl-block">
					<use xlink:href="#aps-logo-full-color"></use>
				</svg>
				<svg width="80" height="30" class="d-lg-none d-xl-none">
					<use xlink:href="#aps-logo-small-color"></use>
				</svg>
			</a>
			
			<!--- MENU --->
			<ul class="navbar-nav ml-auto navbar-gray ac-c d-f ai-c">
				
				@include('layouts.app.off-canvas-menu', ['items' => $mainMenu->roots()])
			
			</ul>
		
		</div>
	
	</nav>
	
	<div itemscope itemtype="http://schema.org/WebSite" class="main-menu-search" :class="{ open: search}">
		
		<link itemprop="url" href="{{ url('/') }}"/>
		
		<form class="search-form"
		      method="GET"
		      action="{{ route('app.search') }}"
		      role="search"
		      itemprop="potentialAction"
		      itemscope
		      itemtype="http://schema.org/SearchAction">
			
			<meta itemprop="target" content="{{ route('app.search') }}?q={q}"/>
			
			<input class=""
			       ref="topSearchInput"
			       type="search" name="q"
			       itemprop="query-input"
			       placeholder="Type to search..."
			       required/>
			
			<button type="submit" class="">
				<vue-svg name="icon-search" square="24"></vue-svg>
			</button>
			
			<button type="button" class="" @click="search = ! search, $refs.topSearchInput.value = ''">
				<vue-svg name="icon-x" square="24"></vue-svg>
			</button>
			
		</form>
	</div>
	
</header>