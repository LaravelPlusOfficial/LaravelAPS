@component('components.forms.input', [
	'type' => 'email',
	'label' => isset($label) ? $label : null,
	'name' => $name,
	'value' => isset($value) ? $value : null,
	'help' => isset( $help) ? $help : null,
	'helpLabel' => isset($helpLabel) ? $helpLabel : null
])
@endcomponent