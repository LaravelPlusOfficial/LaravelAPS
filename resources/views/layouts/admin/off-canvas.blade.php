<section class="off-canvas p-fx t-0 l-0 grad-primary-v of-ya" ref="offCanvasMenu" v-touch.swipeleft="toggleOffCanvas">
	
	<div class="off-canvas-overlay hide-above-lg" @click="toggleOffCanvas()"></div>
	
	<div class="off-canvas-logo">
		
		<div class="d-f jc-c">
			<a href="/" class="logo-lg">
				<vue-svg name="aps-logo-full-white" :width="150" :height="48"></vue-svg>
			</a>
		</div>
		
		<div class="d-f fxg-1 jc-sb ai-c">
			<a href="/" class="d-f logo-small">
				<vue-svg name="aps-logo-small-white" :width="50" :height="56"></vue-svg>
			</a>
			<a href="/" class="d-f close-switch" @click.prevent="toggleOffCanvas()">
				<vue-svg name="icon-x-circle" :width="20" :height="20" classes="ml-2 fill-white"></vue-svg>
			</a>
		</div>
		
	</div>
	
	<div class="dropdown-divider off-canvas-divider m-0"></div>
	
	<div class="off-canvas-content" v-cloak>
		
		<ul class="nav flex-column off-canvas-nav mt-3 mb-3" v-vertical-menu:off-canvas-nav>
			@include('layouts.admin.off-canvas-menu', ['items' => $mainMenu->roots()])
		</ul>
	
	</div>

</section>