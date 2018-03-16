<div class="row">
	
	<div class="col-lg-12 mb-3">
		<h1 class="txt-6 fsz-md tt-u ls-12 d-f ai-c fill-gray">
			
			@if(isset($icon))
				<vue-svg name="{{ $icon }}" square="20"></vue-svg>
			@endif
			
			<span class="ml-2">{{ $slot }}</span>
		</h1>
	</div>

</div>