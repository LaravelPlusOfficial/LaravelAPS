@extends('layouts.admin.master')

@section('content')
	<div class="container-fluid mb-5 pb-5" v-cloak>
		
		@pageTitle(['icon' => 'icon-settings']) Create Setting @endpageTitle
		
		<div class="card bg-light mb-3">
			
			<div class="card-body">
				
				@alert @endalert
				
				@form([ 'method' => 'POST', 'action' => route('admin.setting.store') ])
				
				@input(['label' => 'label', 'name' => 'label', 'placeholder' => 'Setting label', 'required' => true]) @endinput
				
				@include('admin.settings.partials._group-select')
				
				@select(['label' => 'type', 'name' => 'type', 'required' => true])
				
				@slot('options')
					<option value="input" selected>Input</option>
					<option value="number">Number</option>
					<option value="email">Email</option>
					<option value="url">Url</option>
					<option value="password">Password</option>
					<option value="select">select</option>
					<option value="checkbox">checkbox</option>
					<option value="radio">Radio</option>
					<option value="color">Color</option>
					<option value="image">Image</option>
					<option value="tel">Telephone</option>
				@endslot
				
				@endselect
				
				@textarea([
					'label' => 'Comma seperated list of options',
					'helpLabel' => '<span class="tt-n">e.g. value1 => label1, value2 => label2</span>',
					'name' => 'options',
					'placeholder' => 'value1 => label1, value2 => label2 ...'
				]) @endtextarea
				
				@input(['label' => 'help', 'name' => 'Help', 'placeholder' => 'Help text']) @endinput
				
				@input([
					'label' => 'Label help text',
					'name' => 'help_label',
					'placeholder' => 'Help text shown beside label'
				]) @endinput
				
				@checkbox([
					'label' => 'Make it disabled',
					'name' => 'disable',
					'value' => true
				])
				@endcheckbox
				
				<div class="d-f jc-sb mt-3">
					
					@button(['type' => 'submit']) Add setting @endbutton
					
					@button([
						'type' => 'button' ,
						'href' => route('admin.setting.index'),
						'color' => 'secondary',
					]) Add setting @endbutton
				
				</div>
				
				@endform
			
			</div>
		</div>
	
	</div>

@endsection