<section class="off-canvas p-fx t-0 l-0 grad-primary-v of-ya" ref="offCanvasMenu">
	
	<div class="off-canvas-logo d-f jc-sb ac-c ai-c">
		
		<a href="/" class="d-f logo-lg">
			<vue-svg name="lp-logo-full-white" :width="210" :height="56"></vue-svg>
		</a>
		<a href="/" class="d-f logo-small">
			<vue-svg name="lp-logo-small-white" :width="40" :height="56"></vue-svg>
		</a>
		<a href="/" class="d-f close-switch" @click.prevent="toggleOffCanvas()">
			<vue-svg name="icon-x-circle" :width="20" :height="20" classes="ml-2 fill-white"></vue-svg>
		</a>
	</div>
	
	<div class="dropdown-divider off-canvas-divider m-0"></div>
	
	<div class="off-canvas-content" v-cloak>
		
		<ul class="nav flex-column off-canvas-nav mt-3 mb-3" v-vertical-menu:off-canvas-nav>
			@include('layouts.admin.off-canvas-menu', ['items' => $mainMenu->roots()])
		</ul>
	
	</div>

</section>