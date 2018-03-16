@extends('layouts.admin.master')

@section('content')
	
	<taxonomies inline-template
	            taxonomies-url="{{ route('admin.category.index') }}"
	            taxonomy-singular="category"
	            taxonomy-plural="categories">
		<div class="container-fluid" v-cloak>
			
			@pageTitle(['icon' => 'icon-folder']) Categories <small>(@{{ count }})</small> @endpageTitle
			
			<div class="row pb-5">
				
				<!--- List --->
				<div class="col-lg-8 pb-4">
					
					<div class="card">
						<div class="card-body">
							<div class="bg-gray1 pt-2 pl-3 pr-3">
								
								<taxonomies-list :taxonomies="taxonomies"
								                 :taxonomies-url="taxonomiesUrl"
								                 v-if="!fetching"></taxonomies-list>
								
								<div class="d-f jc-c mt-5 mb-5" v-if="fetching">
									<loader :loading="true"></loader>
								</div>
								
								<p v-if="! fetching && taxonomies.length <= 0">No Categories to show</p>
							
							</div>
						
						</div>
					</div>
				
				</div>
				
				<!--- Form --->
				<div class="col-lg-4">
					
					<div class="card">
						
						<div class="card-body">
							
							<h5 class="card-title label">Add Category</h5>
							
							<!--- New category Form --->
							@form
							
								@slot('vSubmit')
									@submit.prevent="taxonomy.id ? update() : add()"
								@endslot
							
								<!--- Category Name --->
								@input()
								
									@slot('label') name @endslot
								
									@slot('name') name @endslot
								
									@slot('placeholder') Category name @endslot
								
									@slot('vModel') taxonomy.name @endslot
								
									@slot('vChange') taxonomy.errors.clear('name') @endslot
									
									@slot('help') @{{ taxonomy.name | strCount(70) }} @endslot
									
									@slot('vError') taxonomy @endslot
								
								@endinput
								
								<!--- Category Slug --->
								@input(['disabled' => true])
								
									@slot('label') Slug @endslot
									
									@slot('name') Slug @endslot
									
									@slot('placeholder') Category slug @endslot
									
									@slot('vModel') taxonomy.slug @endslot
									
									@slot('vChange') taxonomy.errors.clear('slug') @endslot
									
									@slot('help') @{{ taxonomy.slug | strCount(70) }} @endslot
									
									@slot('vError') taxonomy @endslot
									
									@slot('vIf') taxonomy.id @endslot
								
								@endinput
								
								<!--- Category Description --->
								@textarea()
								
									@slot('label') Description @endslot
									
									@slot('name') description @endslot
									
									@slot('placeholder') Description @endslot
									
									@slot('vModel') taxonomy.description @endslot
									
									@slot('vChange') taxonomy.errors.clear('description') @endslot
									
									@slot('help') @{{ taxonomy.description | strCount(160) }} @endslot
									
									@slot('vError') taxonomy @endslot
									
								@endtextarea
								
								<!--- Category Parent --->
								@select()
								
									@slot('label') Parent category @endslot
									
									@slot('name') parent_id @endslot
								
									@slot('vModel') taxonomy.parent_id @endslot
									
									@slot('options')
										
										<option value="null">Select parent taxonomy...</option>
								
										<option v-for="(category, index) in flatTaxonomies"
										        :value="category.id"
										        v-text="category.name"
										></option>
										
									@endslot
								
								@endselect
								
								<div class="d-f jc-sb">
									
									@button(['type' => 'submit', 'loader' => true])
									
										<span>
                                            <span v-show="!taxonomy.id">Add</span>
                                            <span v-show="taxonomy.id">Update</span>
                                        </span>
									
									@endbutton
									
									@button(['color' => 'secondary'])
									
										@slot('vClick') @click="reset()" @endslot
									
										Reset
									
									@endbutton
									
								</div>
							
							@endform
						
						</div>
					
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</taxonomies>
@endsection