@php

	$isLarge = isset($isLarge) ? 'btn-lg' : 'btn-sm';

	$color = isset($color) ? "btn-{$color}" : 'btn-primary';
	
	$vClick = isset($vClick) ? $vClick : '';

	$loader = isset($loader) ? ' :class="{ loader: taxonomy.processing }" ' : '';

	$classes = isset($classes) ? " {$classes} " : '';

	$href = isset($href) ? $href : '';

	$type = isset($type) ? $type : 'button';

	$tag = isset($href) ? "a" : "button";

@endphp

@if($href)
	<a href="{{ $href }}"
	        {{ $vClick }}
	        {!! $loader !!}
	        class="btn {{ $isLarge }} {{ $color }} tt-u ls-12{{ $classes }}">
		{{ $slot }}
	</a>
@else
	<button type="{{ $type }}"
	        {{ $vClick }}
	        {!! $loader !!}
	        class="btn {{ $isLarge }} {{ $color }} tt-u ls-12{{ $classes }}">
		{{ $slot }}
	</button>
@endif