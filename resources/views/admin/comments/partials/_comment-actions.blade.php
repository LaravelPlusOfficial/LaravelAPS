<div class="d-f">
	
	@switch($comment->status)
		
		@case('approved')
		
		<form method="POST" action="{{ route('admin.comment.update', $comment->id) }}"
		      class="mr-2">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}
			<input type="hidden" value="spam" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-orange p-0">
				spam
			</button>
		</form>
		
		<form method="POST"
		      @submit.prevent="confirmAndSubmit('Are you sure you want to delete this comment')"
		      action="{{ route('admin.comment.destroy', $comment->id) }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="hidden" value="delete" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-gray p-0">
				delete
			</button>
		</form>
		
		@break
		
		@case('spam')
		
		<form method="POST"
		      class="mr-2"
		      action="{{ route('admin.comment.update', $comment->id) }}">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}
			<input type="hidden" value="approved" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-green p-0">
				approve
			</button>
		</form>
		
		<form method="POST"
		      class=""
		      @submit.prevent="confirmAndSubmit('Are you sure you want to delete this comment')"
		      action="{{ route('admin.comment.destroy', $comment->id) }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="hidden" value="delete" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-gray p-0">
				delete
			</button>
		</form>
		
		@break
		
		@case('pending')
		
		<form method="POST"
		      class="mr-2"
		      action="{{ route('admin.comment.update', $comment->id) }}">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}
			<input type="hidden" value="approved" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-green p-0">
				approve
			</button>
		</form>
		
		<form method="POST"
		      class=""
		      @submit.prevent="confirmAndSubmit('Are you sure you want to delete this comment')"
		      action="{{ route('admin.comment.destroy', $comment->id) }}">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<input type="hidden" value="delete" name="status">
			<button type="submit"
			        class="btn btn-sm btn-link fsz-xs tt-u ls-11 c-gray p-0">
				delete
			</button>
		</form>
		
		@break
	
	
	@endswitch

</div>