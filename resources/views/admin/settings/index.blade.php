@extends('layouts.admin.master')

@section('content')
	<div class="container-fluid mb-5 pb-5" v-cloak>
		
		@pageTitle(['icon' => 'icon-settings']) Settings @endpageTitle
		
		@alert @endalert
		
		
		<div id="settings-collection" class="row">
			
			<div class="col-lg-8">
				
				@foreach($settingsCollection as $group => $settings)
					
					@include('admin.settings.partials._settings')
				
				@endforeach
				
			</div>
			
			<!--- Delete form --->
			<div class="col-lg-4">
				
				@include('admin.settings.partials._delete-form', ['settingsCollection' => $settingsCollection])
				
				{{--@include('admin.settings.partials._social-auto-post-widget', ['settingsCollection' => $settingsCollection])--}}
			
			</div>
		
		</div>
	
	</div>

@endsection
