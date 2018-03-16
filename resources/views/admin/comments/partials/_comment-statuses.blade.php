@switch($comment->status)
	
	@case('approved')
	<span class="badge badge-success fsz-xxs tt-u ls-12 ml-2">Approved</span>
	@break
	
	@case('spam')
	<span class="badge badge-danger fsz-xxs tt-u ls-12 ml-2">spam</span>
	@break
	
	@case('pending')
	<span class="badge badge-info fsz-xxs tt-u ls-12 ml-2">pending</span>
	@break
	
@endswitch