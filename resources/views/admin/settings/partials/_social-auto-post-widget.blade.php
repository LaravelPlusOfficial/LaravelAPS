<div class="card mt-4">
	
	<div class="card-body">
		
		<h5 class="card-title label">Enable / Disable Auto post service for following providers</h5>
		
		<hr>
		
		<social-auto-post
				to-provider-url="{{ route('social.auto.publish.enable.provider', '%provider%') }}"
				:providers-status="{{ json_encode($providersStatus) }}"></social-auto-post>
	
	</div>

</div>