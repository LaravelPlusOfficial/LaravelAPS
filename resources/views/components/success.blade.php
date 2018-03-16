@php($messages = isset($messages) ? $messages : [])

<div class="grad-success p-3 bdr-5">
	
	<ul class="list-unstyled mb-0 p-0">
		@foreach($messages as $msg)
			<li class="c-white fsz-sm mb-0 fs-i ls-11">{{ $msg }}</li>
		@endforeach
	</ul>


</div>