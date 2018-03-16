@extends('layouts.admin.master')

@section('content')
	
	<div class="container-fluid" v-cloak>
		
		@pageTitle(['icon' => 'icon-user'])
		{{ $profileUser->full_name }}
		<small class="c-gray">Profile</small>
		@endpageTitle
		
		<form action="{{ route('admin.profile.update', $profileUser->id) }}" method="POST" class="mb-5 pb-5">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}
			
			<div class="row">
				
				<div class="col-lg-3">
					
					<div class="card">
						
						<avatar src="{{ $profileUser->avatar }}"
						        avatar-url="{{ route('admin.avatar.update', $profileUser->id) }}"></avatar>
					
					</div>
				
				
				</div>
				
				<div class="col-lg-9">
					
					<div class="card">
						
						<div class="card-body">
							
							@if ($errors->any())
								<div class="bgc-red c-white p-3 mb-2">
									<ul class="list-unstyled mb-0">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							
							<div class="form-row">
								
								<!--- First Name --->
								<div class="form-group col-lg-4 col-md-4 col-sm-12">
									<label for="name" class="">Name</label>
									<input type="text"
									       name="name"
									       id="name"
									       class="form-control"
									       value="{{ old('name') ?? optional($profileUser)->name }}"
									       placeholder="Name...">
								</div>
							
							</div>
							
							<!--- Email --->
							<div class="form-group">
								<label for="email" class="">Email</label>
								<input type="email"
								       name="email"
								       id="email"
								       disabled
								       class="form-control disabled"
								       value="{{ old('email') ?? optional($profileUser)->email }}"
								       placeholder="First name...">
							</div>
							
							<!--- Introduction --->
							<div class="form-group">
								<label for="introduction" class="">Introduction</label>
								<textarea name="introduction"
								          id="introduction"
								          class="form-control"
								          rows="5"
								          placeholder="First name..."
								>{{ old('introduction') ?? optional($profileUser)->introduction }}</textarea>
							</div>
							
							<h2 class="fsz-sm tt-u bdB mb-3 pb-1 ls-12">
								Socials
								<small class="c-gray">Queries in the link will be removed</small>
							</h2>
							
							<!--- Socials --->
							<div class="row">
								
								@foreach(config('aps.users.allowed_social_links') as $linkName => $linkType)
									
									<div class="col-lg-6">
										<div class="form-group">
											<label for="social_links"
											       class="">{{ title_case(str_replace('_', ' ', $linkName )) }}</label>
											<input type="text"
											       name="social_links[{{ $linkName }}]"
											       id="social_links"
											       class="form-control disabled"
											       value='{{ old("social_links.{$linkName}") ?? optional($profileUser->social_links)[$linkName] }}'
											       placeholder="{{ title_case(str_replace('_', ' ', $linkName )) }}...">
										</div>
									</div>
								
								@endforeach
							
							</div>
							
							<!--- Roles --->
							@include('admin.profile._partials.roles')
						
						<!--- Permissions --->
							@include('admin.profile._partials.permissions')
							
							<div class="">
								<button type="submit" class="btn btn-primary fsz-sm ls-12 tt-u">
									update
								</button>
							</div>
						
						</div>
					
					</div>
				
				</div>
			
			</div>
		
		</form>

@endsection