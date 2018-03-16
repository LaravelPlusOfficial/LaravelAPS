<div class="card">
	
	<div class="card-body">
		
		<h5 class="card-title label">Delete Setting</h5>
		
		<hr>
		
		@form([
			'method' => 'post',
			'action' => route('admin.setting.destroy'),
			'delete' => 'true',
			'vSubmit'=> "@submit.prevent=\"confirmAndSubmit('Are you sure you want to delete this setting')\""
		])
		
		@select(['label' => 'Select setting to delete', 'name' => 'key', 'help' => 'Deleted setting won\'t be reversed'])
		
		@slot('options')
			
			<option>Select setting...</option>
			
			@foreach($settingsCollection as $group => $settings)
				<optgroup label="{{ strtoupper(str_replace('_', ' ', $group)) }}">
					
					@foreach($settings as $setting)
						<option value="{{ $setting->key }}">
							
							{{ $setting->label }}
						
						</option>
					@endforeach
				
				</optgroup>
			@endforeach
		
		@endslot
		
		@endselect
		
		@button(['type' => 'submit'])
		Delete
		@endbutton
		
		@endform
	
	</div>

</div>