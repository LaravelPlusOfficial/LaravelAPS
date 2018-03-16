@php
	$label = isset($label) ? $label : null;
	
	$helpLabel = isset($helpLabel) ? " <span class='c-gray'>({$helpLabel})</span>" : '';

	$for = isset($label) ? str_slug($label) : null;
	
	$id = isset($id) ? $id : $for;
	
	$vModel = isset($vModel) ? " v-model='{$vModel}' " : '';
	
	$vChange = isset($vChange) ? ' @change="'. $vChange .'" ' : '';
	
	$vIf = isset($vIf) ? " v-if='{$vIf}' " : '';
	
	$vShow = isset($vShow) ? " v-if='{$vShow}' " : '';
	
	$disabled = isset($disabled) && $disabled ? 'disabled' : '';
	
	$value = isset($value) ? $value : '';

	$class = isset($class) ? " {$class} " : '';

	$required = isset($required) ? " required " : '';

@endphp

<div class="form-group{{$class}}" {!! $vIf !!} {!! $vShow !!}>
	
	@if($label)
		<label for="{{ $for }}">{{ $label }} {!! $helpLabel !!}</label>
	@endif
	
	<select class="form-control"
	        name="{{ $name }}"
	        {!! $vChange !!}
	        id="{{ $id ?? '' }}"
			{!! $required !!}
			{!! $vModel !!}
			{!! $disabled !!}>
		
		@if(isset($options))
			{{ $options }}
		@endif
	
	</select>
	
	@if(isset($help))
		<p class="fsz-xs c-gray fs-i ls-11 mt-1 mb-0">
			{{ $help }}
		</p>
	@endif()
	
	@if(isset($vError))
		<p class="c-red mb-0 fs-i fsz-sm"
		   v-if="{{ $vError }}.errors.has('{{ $name}}')"
		   v-text="{{ $vError }}.errors.get('{{ $name}}')"></p>
	@endif

</div>