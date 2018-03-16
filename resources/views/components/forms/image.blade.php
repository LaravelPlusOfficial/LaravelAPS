@php
	$type = isset($type) ? $type : 'text';
	
	$label = isset($label) ? str_replace('_' , ' ', $label) : null;
	
	$helpLabel = isset($helpLabel) ? " <span class='c-gray'>({$helpLabel})</span>" : '';

	$for = isset($label) ? str_slug($label) : null;
	
	$id = isset($id) ? $id : $for;
	
	$vModel = isset($vModel) ? " v-model='{$vModel}' " : '';
	
	$vChange = isset($vChange) ? ' @change="'. $vChange .'" ' : '';
	
	$placeholder = isset($placeholder) ? " placeholder='{$placeholder}...' " : '';
	
	$vIf = isset($vIf) ? " v-if='{$vIf}' " : '';
	
	$vShow = isset($vShow) ? " v-if='{$vShow}' " : '';
	
	$disabled = isset($disabled) ? 'disabled' : '';
	
	$value = isset($value) ? $value : '';

	$class = isset($class) ? " {$class} " : '';

	$required = isset($required) ? " required " : '';

	$maxWidth = isset($maxWidth) ? " max-width='{$maxWidth}' " : '';

	$deleteInput = isset($deleteInput) ? $deleteInput : 'false';

	$info = isset($info) ? true : false;

@endphp

<div class="form-group{{ $class }}" {!! $vIf !!} {!! $vShow !!}>
	
	@if($label)
		<label for="{{ $for }}">
			{{ $label }}
			{!! $helpLabel !!}
		</label>
	@endif
	
	<file-selector src="{{ $value }}"
	               {!! $maxWidth !!}
	               name="{{ strtolower($name) }}"
	               :delete-input="{{ $deleteInput }}">
		
		@if(isset($buttonSlot))
			{!! $buttonSlot !!}
		@endif
		
		{{--@if($info)--}}
		{{--@php--}}
		{{--$modalId = isset($modalId) ? "@click=\"\$modal.show('{$modalId}')\"" : null--}}
		{{--@endphp--}}
		{{----}}
		{{--<button class="bg-n p-0 m-0 lh-1 bd-n fill-gray fill-gray-dark-hv cur-p o-n-fc ml-2 otl-n-fc"--}}
		{{--type="button"--}}
		{{--{!! $modalId !!}--}}
		{{--slot="buttons">--}}
		{{--<vue-svg name="icon-eye" square="20"></vue-svg>--}}
		{{--</button>--}}
		{{----}}
		{{--@endif--}}
	
	</file-selector>

</div>