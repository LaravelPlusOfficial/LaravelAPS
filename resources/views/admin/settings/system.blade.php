@extends('layouts.admin.master')

@section('content')
	<div class="container-fluid mb-5 pb-5" v-cloak>
		
		@pageTitle(['icon' => 'icon-settings']) System Variables @endpageTitle
		
		
		<div id="settings-collection" class="row">
			
			<div class="col-lg-12">
				
				<!--- Form start --->
				@form(['method' => 'post', 'action' => route('admin.env.update'), 'update' => true])
				
				<div class="card">
					
					<div class="card-body">
						
						
						<h5 class="card-title label mb-4">Environment Variables</h5>
						
						<hr>
						
						@foreach($envVars as $key => $value)
							
							@component('components.forms.input', [
								'label' => $key,
								'name' => $key,
								'value' => $value,
							])
							@endcomponent
						
						@endforeach
					
					</div>
					
					<div class="card-footer">
						
						@button(['type' => 'submit'])
						Update
						@endbutton
					
					</div>
				
				
				</div>
				
				@endform
			
			</div>
		
		</div>
	
	</div>

@endsection
