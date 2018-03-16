@extends('layouts.admin.master')

@section('content')
	
	<div class="container-fluid" v-cloak>
		
		@pageTitle(['icon' => 'icon-user'])
		Users
		<small>({{ $users->total() }})</small>
		@endpageTitle
		
		<div class="row">
			
			<div class="col-lg-12 mb-5">
				
				<div class="table-responsive mt-4">
					<table class="table table-bordered table-hover">
						<thead>
						<tr>
							<th scope="col"><span class="fsz-xs tt-u ls-12">#</span></th>
							<th scope="col"><span class="fsz-xs tt-u ls-12">Name</span></th>
							<th scope="col"><span class="fsz-xs tt-u ls-12">Member Since</span></th>
						</tr>
						</thead>
						<tbody>
						@php($i = ($users->currentpage()-1)* $users->perpage())
						
						@foreach($users as $user)
							<tr>
								<td>{{ ++$i }}</td>
								<td>
									<a href="{{ route('admin.profile.edit', $user->id) }}" class="d-f ai-c">
										
										@if($user->avatar)
											<img src="{{ $user->avatar }}" class="bdr-50 mr-3"
											     style="width: 40px; height: 40px">
										@else
											<vue-svg name="icon-user" square="40" classes="fill-gray mr-3"></vue-svg>
										@endif
										<span>{{ $user->name }}</span>
									</a>
								</td>
								<td>{{ $user->created_at->diffForHumans() }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				
				<div class="mt-4">
					{{ $users->links() }}
				</div>
			
			</div>
		
		</div>
	
	</div>

@endsection