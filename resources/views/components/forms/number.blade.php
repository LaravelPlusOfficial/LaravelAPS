@component('components.forms.input', [
	'type' => 'number',
	'label' => isset($label) ? $label : null,
	'name' => $name,
	'value' => isset($value) ? $value : null,
	'help' => isset( $help) ? $help : null,
	'helpLabel' => isset($helpLabel) ? $helpLabel : null,
	'vModel' => isset($vModel) ? $vModel : null,
	'vChange' => isset($vChange) ? $vChange : null,
	'placeholder' => isset($placeholder) ? $placeholder : null,
	'vIf' => isset($vIf) ? $vIf : null,
	'vShow' => isset($vShow) ? $vShow : null,
	'disabled' => isset($disabled) ? $disabled : null,
])
@endcomponent