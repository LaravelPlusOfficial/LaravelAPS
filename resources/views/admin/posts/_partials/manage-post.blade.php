@php
	$post = isset($post) ? $post  : null;
	
	$postType = isset($postType) ? $postType  : 'post';

	$isPost = $postType == 'post';

	$defaultSocialAutoPost = json_encode([
		'facebook' => setting('social_auto_post_facebook', 'false'),
		'twitter' => setting('social_auto_post_twitter', 'false')
	]);

@endphp

<manage-post inline-template
             post-url="{{ $postUrl }}"
             :is-page="'{{ $postType == 'page' }}'"
             post-type="{{ $postType }}"
             republish-to-social-media-url="{{ route('admin.post.republish-to-social-media', ['%postId%', '%provider%']) }}"
             :default-social-auto-post="{{ $defaultSocialAutoPost }}"
             :post-data="{{ $post ? json_encode($post, 1) : 'null' }}">
	
	<form action="{{ route('admin.post.store') }}" @submit.prevent @keydown="post.errors.clear($event.target.name)">
		<div class="container-fluid" v-cloak>
			
			<div class="row pb-5">
				
				<div class="col-lg-12 mb-3">
					<h1 class="fsz-md ls-12 tt-u">

                        <span v-if="!post.id">Create
                            <span v-if="!isPage">Post</span>
                            <span v-if="isPage">Page</span>
                        </span>
						
						<span v-if="post.id" class="d-f ac-c ai-c">

                            <span>Edit
                                <span v-if="!isPage">Post</span>
                                <span v-if="isPage">Page</span>
                                &nbsp;
                            </span>

                            <span class="badge badge-success fsz-xs" v-if="post.status === 'published'">Published</span>

                            <span class="badge badge-secondary fsz-xs" v-if="post.status === 'draft'">Draft</span>

                        </span>
					</h1>
					
					<h4 class="fsz-md c-gray d-f ac-c ai-c" v-if="post.path">
						<span class="">@{{ post.title }}</span>
						<a :href="post.path" class="ml-2 fill-primary-hv" target="_blank">
							<vue-svg name="icon-external-link" square="16"></vue-svg>
						</a>
					</h4>
					
					@if(empty($categories) && $isPost)
						@alert(['messages' => [ 'No Category added', 'Please add category to post content' ]])
						@endalert
					@endif
				
				</div>
				
				<div class="col-lg-8 mb-4">
					
					<div class="card">
						
						<div class="card-body">
							
							<!--- Post Slug --->
							<div class="mb-2" v-if="post.slug">
								<div class="d-f ai-fe">
									<div class="form-group mb-0 fxg-1">
										<label for="slug">Slug</label>
										<input type="text"
										       class="form-control"
										       id="slug"
										       v-model="post.slug"
										       :disabled="!editSlug"
										       placeholder="Title...">
									</div>
									<button type="button"
									        @click="editSlug = !editSlug"
									        class="btn bxs-n-fc fill-gray fill-primary-hv">
										<vue-svg name="icon-edit" square="18"></vue-svg>
									</button>
									<button type="button"
									        v-if="editSlug"
									        @click="update()"
									        class="btn bxs-n-fc fill-gray fill-primary-hv">
										<vue-svg name="icon-check" square="20"></vue-svg>
									</button>
								</div>
								<p class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
									@php($slugCountWarning = "@{{ post.slug | strCount(" . setting('seo_title_length', '70') .  ")}}" )
									{{ $slugCountWarning }}
								</p>
								<p class="c-red mb-0 fs-i fsz-sm"
								   v-if="post.errors.has('slug')"
								   v-text="post.errors.get('slug')"></p>
							</div>
							
							<!--- Post Title --->
							<div class="form-group">
								<label for="title">Title</label>
								<input type="text"
								       class="form-control"
								       id="title"
								       v-model="post.title"
								       placeholder="Title...">
								<p class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
									@php($titleCountWarning = "@{{ post.title | strCount(" . setting('seo_title_length', '70') .  ")}}" )
									{{ $titleCountWarning }}
								</p>
								
								<p class="c-red mb-0 fs-i fsz-sm"
								   v-if="post.errors.has('title')"
								   v-text="post.errors.get('title')"></p>
							</div>
							
							<div class="form-group">
								<label for="body">Content</label>
								
								<wysiwyg name="body"
								         v-model="post.body"
								         placeholder="Write some thing cool...">
								</wysiwyg>
								
								<p class="c-red mb-0 fs-i fsz-sm"
								   v-if="post.errors.has('body')"
								   v-text="post.errors.get('body')"></p>
							</div>
							
							<div class="form-group mb-0">
								<label for="excerpt">Excerpt</label>
								<textarea name="excerpt"
								          id="excerpt"
								          rows="4"
								          v-model="post.excerpt"
								          class="form-control"
								          placeholder="Excerpt..."></textarea>
								<p class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
									@php($descriptionCountWarning = "@{{ post.excerpt | strCount(" . setting('seo_description_length', '70') .  ")}}" )
									{{ $descriptionCountWarning }}
								</p>
								<p class="c-red mb-0 fs-i fsz-sm"
								   v-if="post.errors.has('excerpt')"
								   v-text="post.errors.get('excerpt')"></p>
							</div>
						</div>
					</div>
					
					<div class="card mt-4">
						<div class="card-body">
							
							<div class="form-group">
								<label for="meta-description">Meta Description</label>
								<textarea name="meta['description']"
								          id="meta-description"
								          rows="4"
								          class="form-control"
								          v-model="post.metas.description"
								          placeholder="Meta Discription..."></textarea>
								<p class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
									@php($metaDescCountWarning = "@{{ post.metas.description | strCount(" . setting('seo_description_length', '70') .  ")}}" )
									{{ $metaDescCountWarning }}
								</p>
							</div>
							
							<div class="form-group">
								<label for="robots">Robots</label>
								<select name="robots" id="robots" class="form-control" v-model="post.metas.robots">
									<option value="all">All</option>
									<option value="index, follow">Index & Follow</option>
									<option value="index, nofollow">Index & No-Follow</option>
									<option value="noindex, follow">No-Index & Follow</option>
									<option value="noindex, nofollow">No-Index & No-Follow</option>
								</select>
								<small class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
									Robots tag help search engine bots to index website. For more information visit
									<a href="https://developers.google.com/search/reference/robots_meta_tag"
									   target="_blank">Google Page</a>
								</small>
							</div>
						
						
						</div>
					
					</div>
				
				</div>
				
				<div class="col-lg-4">
					
					<div class="card">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<button type="button"
								        v-if="!post.id"
								        @click="publish()"
								        :class="{loader : saving}"
								        class="btn btn-primary label">
									Publish
								</button>
								<button type="button"
								        v-if="post.id"
								        @click="update()"
								        class="btn btn-primary label">
									Update
								</button>
								<button type="button"
								        v-if="post.id && post.status === 'published'"
								        @click="unPublish()"
								        class="btn btn-secondary label">
									Un Publish
								</button>
								
								<button type="button"
								        v-if="post.id && post.status === 'draft'"
								        @click="publish(true)"
								        class="btn btn-success label">
									Publish
								</button>
								
								<button type="button"
								        v-if="!post.id"
								        @click="draft()"
								        class="btn btn-secondary label">
									draft
								</button>
								
								<a href="#"
								   class="btn fill-gray fill-primary-hv otl-n-fc otl-n-hv bxs-n-fc"
								   v-if="post.id"
								   @click.prevent="deletePost()">
									<vue-svg name="icon-delete" square="20"></vue-svg>
								</a>
							
							</div>
						</div>
					</div>
				
				@if($postType == 'post')
					
					<!--- Post Format --->
						<div class="card mt-4">
							<div class="card-body">
								<h5 class="card-title label">Post Format</h5>
								
								<p class="c-red mb-0 fs-i fsz-sm"
								   v-if="post.errors.has('post_format')"
								   v-text="'Please select post format'"></p>
								
								<div class="bg-gray1 pt-2 pl-3 pr-3">
									
									<ul class="mb-0 tree-ul max-ht-400">
										
										@foreach($postFormats as $format)
											<li>
												<label class="form-radio tt-n fsz-sm">
													<input type="radio"
													       value="{{ str_slug($format, '_') }}"
													       name="post_format"
													       @change="post.errors.clear('post_format')"
													       v-model="post.post_format">
													<span>{{ ucfirst($format) }}</span></label>
											</li>
										@endforeach
									
									</ul>
								
								</div>
							
							</div>
						</div>
						
						<!--- Taxonomies --->
						<div class="card mt-4">
							<div class="card-body">
								<h5 class="card-title label">Category</h5>
								
								@if(!empty($categories))
									
									<p class="c-red mb-0 fs-i fsz-sm"
									   v-if="post.errors.has('category_slug')"
									   v-text="post.errors.get('category_slug')"></p>
									
									<div class="bg-gray1 pt-2 pl-3 pr-3">
										@include('admin.posts._partials.category-list', [
											'categories' => $categories,
											'classes' => 'mb-0 tree-ul max-ht-400'
										])
									</div>
								
								@else
									
									<p class="c-red ta-c fsz-sm tt-u ls-12">No categories to choose</p>
									<p class="ta-c mb-0">Please add categories before publishing</p>
								
								@endif
							
							
							</div>
						</div>
						
						<div class="card mt-4">
							<div class="card-body">
								<h5 class="card-title label">Tags</h5>
								
								@include('admin.posts._partials.tag-input')
							
							</div>
						</div>
				@endif
				
				
				<!--- Featured Image --->
					<div class="card mt-4">
						<div class="card-body">
							<h5 class="card-title label">Featured Image</h5>
							
							<div class="d-f jc-c">
								<img :src="post.featured_image.variations.thumbnail.path"
								     class="mw-100 mt-2"
								     v-if="post.featured_image && post.featured_image.variations.thumbnail.path">
							</div>
							
							<div class="d-f jc-c mt-4">
								<button type="button"
								        @click="selectFeaturedImage()"
								        class="p-0 m-0 lh-1 bg-n bd-n fill-gray fill-primary-hv cur-p">
									<vue-svg name="icon-image" v-if="!post.featured_image_id" square="20"></vue-svg>
									<vue-svg name="icon-edit" v-if="post.featured_image_id" square="20"></vue-svg>
								</button>
								<button type="button"
								        @click="removeFeaturedImage()"
								        class="p-0 m-0 lh-1 bg-n bd-n fill-gray fill-primary-hv cur-p ml-3">
									<vue-svg name="icon-delete" v-if="post.featured_image_id" square="20"></vue-svg>
								</button>
							</div>
							
							@include('admin.media._media-library', ['embed' => 'false'])
						
						</div>
					</div>
					
					<!--- Social Media Share --->
					@if(
						(setting('social_auto_post_facebook') == 'enable') ||
						(setting('social_auto_post_twitter') == 'enable')
					)
						<div class="card mt-4" v-if="!post.id">
							<div class="card-body">
								<h5 class="card-title label">Publish To Social Media</h5>
								
								@if(setting('social_auto_post_facebook') == 'enable')
									<div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
										<label for="auto_post_facebook" class="fsz-sm ls-11 tt-u">
											<input type="checkbox"
											       name="auto_post_facebook"
											       id="auto_post_facebook"
											       v-model="post.metas.auto_post_facebook">
											<span>Facebook</span>
										</label>
									</div>
								@endif
								
								@if(setting('social_auto_post_twitter') == 'enable')
									<div class="toggle mt-4 ta-l fxg-1 w-100 mw-90">
										<label for="auto_post_twitter" class="fsz-sm ls-11 tt-u">
											<input type="checkbox"
											       name="auto_post_twitter"
											       id="auto_post_twitter"
											       v-model="post.metas.auto_post_twitter">
											<span>Twitter</span>
										</label>
									</div>
								@endif
							
							</div>
						</div>
						
						<div class="card mt-4" v-if="post.id">
							<div class="card-body">
								<h5 class="card-title label d-f ai-c">
									<span class=" mr-2">Re-Publish To Social Media</span>
									<loader height="12px" width="2px" v-if="republishingToSocial"></loader>
								</h5>
								
								<div class="d-f jc-sb">
									
									@if(setting('social_auto_post_facebook') == 'enable')
										<button type="button"
										        @click="republishingToSocialProvider('facebook')"
										        class="btn btn-link d-f ai-c tt-u fsz-sm ls-11 c-gray">
											<vue-svg name="icon-facebook-colored" square="20"></vue-svg>
											<span class="ml-2">Facebook</span>
										</button>
									@endif
									
									@if(setting('social_auto_post_twitter') == 'enable')
										<button type="button"
										        @click="republishingToSocialProvider('twitter')"
										        class="btn btn-link d-f ai-c tt-u fsz-sm ls-11 c-gray">
											<vue-svg name="icon-twitter-colored" square="20"></vue-svg>
											<span class="ml-2">Twitter</span>
										</button>
									@endif
								</div>
							
							</div>
						</div>
					@endif
				
				</div>
			
			</div>
		
		</div>
	</form>

</manage-post>

@push('prepend-styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/medium-editor@latest/dist/css/medium-editor.min.css"
	      type="text/css" media="screen" charset="utf-8">
@endpush

@push('append-scripts')
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.23.3/js/medium-editor.min.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.runtime.min.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sortable/0.9.13/jquery-sortable-min.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.21.0/js/vendor/jquery.ui.widget.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.21.0/js/jquery.iframe-transport.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.21.0/js/jquery.fileupload.js"></script>--}}
	{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/medium-editor-insert-plugin/2.5.0/js/medium-editor-insert-plugin.js"--}}
	{{--onload="scriptLoaded('mediumInsertPlugin')"></script>--}}
	
	<script src="{{ mix('js/medium-editor.js') }}" onload="scriptLoaded('mediumEditorLoaded')"></script>
@endpush
