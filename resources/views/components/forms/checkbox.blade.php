@php
	
	$label = isset($label) ? str_replace('_' , ' ', $label) : null;

	$label = isset($label) ? str_replace('_' , ' ', $label) : null;
	
	$helpLabel = isset($helpLabel) ? " <span class='c-gray'>({$helpLabel})</span>" : '';

	$vModel = isset($vModel) ? " v-model='{$vModel}' " : '';
	
	$vChange = isset($vChange) ? ' @change="'. $vChange .'" ' : '';
	
	$vIf = isset($vIf) ? " v-if='{$vIf}' " : '';
	
	$vShow = isset($vShow) ? " v-if='{$vShow}' " : '';

	$disabled = isset($disabled) ? 'disabled' : '';
	
	$value = isset($value) ? $value : null;
	
	$class = isset($class) ? " {$class} " : null;

	$required = isset($required) ? " required " : '';

@endphp

<label class="form-checkbox tt-n fsz-sm">
	<input type="checkbox"
	       name="{{ $name }}"
	       value="{{ $value }}"
	       {{ isset($checked) && $checked ? 'checked' : '' }}
	       {!! $vModel !!}
	       {!! $required !!}
	       {!! $disabled !!}>
	<span>{{ $label }} {!! $helpLabel !!}</span>
</label>

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