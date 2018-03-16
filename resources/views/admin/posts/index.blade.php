@extends('layouts.admin.master')

@section('content')
	
	<div class="container-fluid" v-cloak>
		
		@pageTitle(['icon' => 'icon-edit'])
			Posts
			<small>({{ $posts->total() }})</small>
		@endpageTitle
		
		<div class="row">
			
			<div class="col-lg-12 mb-5">
				
				@if(empty($posts->items()))
					
					<div class="pt-5">
						<div class="d-f jc-c ai-c bgc-gray-light p-5 bdr-10 mt-5">
							<p class="mb-0 mr-2">There is no post to show.</p>
							<a href="{{ route('admin.post.create') }}" class="btn btn-primary btn-sm fsz-sm tt-u ls-12">Add
								Post</a>
						</div>
					</div>
				
				@else
					
					<div class="table-responsive mt-4">
						<table class="table table-bordered table-hover">
							<thead>
							<tr>
								<th scope="col"><span class="fsz-xs tt-u ls-12">#</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Title</span></th>
								{{--<th scope="col"><span class="fsz-xs tt-u ls-12">Type</span></th>--}}
								<th scope="col"><span class="fsz-xs tt-u ls-12">Category</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Published On</span></th>
								<th scope="col"><span class="fsz-xs tt-u ls-12">Created On</span></th>
							</tr>
							</thead>
							<tbody>
							@php($i = ($posts->currentpage()-1)* $posts->perpage())
							
							@foreach($posts as $post)
								
								<tr>
									<td>
										{{ ++$i }}
									</td>
									<td>
										<a href="{{ route('admin.post.edit', $post->id) }}">
											{{ $post->title }}
										</a>
										<p class="mb-0">
											{{ $post->author->name }}
											
											@if($post->status == 'published')
												<span class="badge badge-success fsz-xxs tt-u ls-12">Published</span>
											@else
												<span class="badge badge-secondary fsz-xxs tt-u ls-12">draft</span>
											@endif
											
											{{ $post->post_type }}
										
										</p>
									</td>
									<td>
										{{ $post->category->name }}
									</td>
									<td>{{ $post->publish_at ? $post->publish_at->diffForHumans() : '-'}}</td>
									<td>{{ $post->created_at->diffForHumans() }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
					
					<div class="mt-4">
						{{ $posts->links() }}
					</div>
				
				@endif
			
			</div>
		
		</div>
	
	</div>

@endsection