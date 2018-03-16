@if ($errors->any())
	<div class="grad-error p-3 bdr-5 mb-3 mt-3">
		
		<ul class="list-unstyled mb-0 p-0">
			@foreach ($errors->all() as $error)
				<li class="c-white fsz-sm mb-0 fs-i ls-11">{!! $error !!}</li>
			@endforeach
		</ul>
	
	</div>
@endif

@if (isset($messages))
	<div class="grad-error p-3 bdr-5">
		
		<ul class="list-unstyled mb-0 p-0">
			@foreach ($messages as $message)
				<li class="c-white fsz-sm mb-0 fs-i ls-11">{!! $message !!}</li>
			@endforeach
		</ul>
	
	</div>
@endif
