@foreach($items as $item)
	
	@php( $hasChildren = $item->hasChildren() ? true : false )
	
	<li class="{{ generateMenuItemClass( $item, 'nav-item') }}">
		
		<a class="nav-link d-flex jc-c ai-c fsz-sm tt-u ls-12 fw-600 text-primary"
		   title="{{ $item->title }}"
		   {!! $item->attr('@click.prevent') ? '@click.prevent=' . $item->attr('@click.prevent') : '' !!}
		   {!! $item->attr('onclick') ? 'onclick=' . $item->attr('onclick') : '' !!}
		   href="{!! $item->url() !!}">
			
			@if($item->attr('svg'))
				<vue-svg name="{!! $item->attr('svg') !!}"
				         square="{{ $item->attr('svg-square-size') ? $item->attr('svg-square-size') : '16'}}"
				         classes="mr-1 fill-primary"></vue-svg>
			@endif
			
			@if(! $item->attr('title-hidden'))
				<span class='{{ $item->attr('title-classes') }}'>{{ $item->title }}</span>
			@endif
			
		</a>
		
		
		@if($hasChildren)
			<ul class="submenu">
				@include('layouts.app.off-canvas-menu', ['items' => $item->children() ])
			</ul>
		@endif
	
	</li>

@endforeach