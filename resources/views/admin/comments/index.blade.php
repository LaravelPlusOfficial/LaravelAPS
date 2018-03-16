@extends('layouts.admin.master')

@section('content')
	
	<div class="container-fluid" v-cloak>
		
		@pageTitle(['icon' => 'icon-message-circle'])
			Comments
			<small>({{ $comments->total() }})</small>
		@endpageTitle
		
		<div class="row">
			
			<div class="col-lg-12 mb-5">
				
				@if(empty($comments->items()))
					
					<div class="pt-5">
						<div class="ta-c bgc-gray-light p-5 bdr-10 mt-5">
							<p class="mb-0 mr-2 fsz-sm tt-u ls-11">There are no comments to show.</p>
							<p class="mb-0">Go Post Some comments</p>
						</div>
					</div>
				
				@else
					
					<div class="table-responsive mt-4">
						<table class="table table-bordered table-hover">
							<thead>
							<tr>
								<th scope="col"><span class="fsz-xs tt-u ls-12">#</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Comment</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Response To</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Date</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Posted To</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Status</span></th>
							</tr>
							</thead>
							<tbody>
							@php($i = ($comments->currentpage()-1)* $comments->perpage())
							
							@foreach($comments as $comment)
								
								<tr>
									<td>
										{{ ++$i }}
									</td>
									<td>{!! $comment->body !!}
										
										<div class="d-f ai-c mt-1">
											
											<vue-svg name="icon-user" square="10"></vue-svg>
											
											<h5 class="mb-0 fsz-xs tt-u ls-11 ml-1">Gurinde Chuahn</h5>
											
											<a href="{{ $comment->location()  }}"
											   class="ml-1 fill-primary-hv"
											   target="_blank">
												<vue-svg name="icon-link" square="12"></vue-svg>
											</a>
										
										</div>
										
										@if($user->isAdmin())
											
											@include('admin.comments.partials._comment-actions')
											
										@else
											
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
											
										@endif
									
									</td>
									<td>
										{{ str_limit(optional($comment->parent)->body, 300) ?: '-' }}
									</td>
									<td>
										{{ $comment->created_at->diffForHumans() }}
									</td>
									<td>
										<a href="{{ $comment->post->path }}"
										   class="ml-1 fill-primary-hv"
										   target="_blank">
											<vue-svg name="icon-link" square="12"></vue-svg>
										</a>
										
										{{ str_limit($comment->post->title, 30) }}
										
									</td>
									<td>
										@include('admin.comments.partials._comment-statuses')
									</td>
								
								</tr>
							
							@endforeach
							</tbody>
						</table>
					</div>
					
					<div class="mt-4">
						{{ $comments->links() }}
					</div>
				
				@endif
			
			</div>
		
		</div>
	
	</div>

@endsection