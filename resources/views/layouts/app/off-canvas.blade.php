<section class="off-canvas p-fx tlb-0 bg-primary z-fixed-1 grad-primary-v" ref="offCanvasMenu" v-touch.swipeleft="toggleOffCanvas">
	
	<div class="off-canvas-overlay hide-above-lg" @click="toggleOffCanvas()"></div>
	
	<div class="off-canvas-content">
		
		<!-- Logo -->
		<div class="off-canvas-logo d-f ac-c jc-sb ai-c">
			<a href="/" class="d-f jc-c ac-c ai-c">
				<vue-svg name="aps-logo-small-white" width="60" height="30"></vue-svg>
			</a>
			<a href="#" @click.prevent="toggleOffCanvas()" class="d-f fill-white">
				<vue-svg name="icon-x" square="20"></vue-svg>
			</a>
		</div>
		
		<!-- Search -->
		<div class="off-canvas-search pt-4 pb-4 d-flex" itemscope itemtype="http://schema.org/WebSite">
			<link itemprop="url" href="{{ url('/') }}"/>
			
			<form action="{{ route('app.search') }}"
			      method="GET"
			      role="search"
			      itemprop="potentialAction"
			      itemscope
			      itemtype="http://schema.org/SearchAction"
			      class="d-flex justify-content-center w-100">
				
				<meta itemprop="target" content="{{ route('app.search') }}?q={q}"/>
				
				<input type="search"
				       name="q" itemprop="query-input"
				       placeholder="Type to search..."
				       required
				       class="w-100">
			
			</form>
		
		</div>
		
		<!-- Menu -->
		<ul class="nav flex-column off-canvas-nav mt-3 app-side-menu">
			
			@include('layouts.app.off-canvas-menu', ['items' => $mainMenuOffCanvas->roots()])
		
		</ul>
		
		
	
	</div>

</section>