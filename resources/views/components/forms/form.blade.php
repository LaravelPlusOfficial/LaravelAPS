@php
	
	$class = isset($class) ? " {$class} " : '';

	$vSubmit = isset($vSubmit) ? $vSubmit : '';
	
	$file = isset($file) ? " enctype='multipart/form-data' " : '';

@endphp

<form action="{{ isset($action) ? $action : '' }}"
      method="{{ isset($method) ? $method : '' }}"
      {!! $file !!}
      {!! $vSubmit !!}
      class="{{ $class }}">
	
	@if (isset($method) && (strtolower($method) != 'get') )
		{{ csrf_field() }}
	@endif
	
	@if(isset($delete))
		{{ method_field('DELETE') }}
	@endif
	
	@if(isset($update))
		{{ method_field('PATCH') }}
	@endif
	
	{{ $slot }}

</form>