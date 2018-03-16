<div class="card">
	<div class="card-body">
		
		<div class="row">
			
			<div class="col-lg-6 col-md-6 col-sm-6">
				<h5 class="card-title label">
					Assigned Roles
				</h5>
				
				<ul class="list-unstyled">
					@foreach($dashboardUserAcl['roles'] as $slug => $role)
						<li class="d-f mb-2 ai-c">
							<vue-svg name="icon-user-check" square="16"></vue-svg>
							<div class="ml-2">
								{{ ucwords($role['label']) }}
							</div>
						</li>
					@endforeach
				</ul>
			</div>
			
			<div class="col-lg-6 col-md-6 col-sm-6">
				<h5 class="card-title label">Allowed Permissions</h5>
				
				<ul class="list-unstyled">
					@foreach($dashboardUserAcl['permissions'] as $slug => $permission)
						<li class="d-f mb-2 ai-c">
							<vue-svg name="icon-check" square="16"></vue-svg>
							<div class="ml-2">
								{{ ucwords($permission['label']) }}
							</div>
						</li>
					@endforeach
				</ul>
			</div>
		
		</div>
	
	</div>
</div>