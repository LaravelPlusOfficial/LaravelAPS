@extends('layouts.admin.master')

@section('content')
	
	<div class="container-fluid mb-5 pb-5" v-cloak>
		
		@pageTitle(['icon' => 'icon-dashboard'])
			Dashboard
		@endpageTitle
		
		<div class="row">
			
			<div class="col-lg-7">
				
				<div class="row">
					
					@include('admin.dashboard.partials._stats')
				
				</div>
				
				<div class="row">
					
					<div class="col-lg-12 mb-4">
					
						@include('admin.dashboard.partials._roles-permissions')
					
					</div>
				
				</div>
			
			</div>
			
			<div class="col-lg-5">
				
				@include('admin.dashboard.partials._profile-widget')
			
			</div>
		
		</div>
	
	</div>

@endsection