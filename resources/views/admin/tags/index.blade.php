@extends('layouts.admin.master')

@section('content')
	
	<taxonomies inline-template taxonomies-url="{{ route('admin.tag.index') }}" taxonomy-singular="tag"
	            taxonomy-plural="tags">
		<div class="container-fluid" v-cloak>
			
			@pageTitle(['icon' => 'icon-tag']) Tags
			<small>(@{{ count }})</small> @endpageTitle
			
			<div class="row pb-5">
				
				<!--- List --->
				<div class="col-lg-8 pb-4">
					
					<div class="card">
						<div class="card-body">
							<div class="bg-gray1 pt-2 pl-3 pr-3">
								
								<taxonomies-list :taxonomies="taxonomies"
								                 :taxonomies-url="taxonomiesUrl"
								                 :taxonomy-singular="taxonomySingular"
								                 v-if="!fetching"></taxonomies-list>
								
								<div class="d-f jc-c mt-5 mb-5" v-if="fetching">
									<loader :loading="true"></loader>
								</div>
								
								<p v-if="! fetching && taxonomies.length <= 0">No Tags to show. Please addd some
									tags</p>
							
							</div>
						
						</div>
					</div>
				
				</div>
				
				<!--- Form --->
				<div id="create-new-taxonomy" class="col-lg-4">
					
					<div class="card">
						
						<div class="card-body">
							
							<h5 class="card-title label">Add tag</h5>
							
							@form
							
							@slot('vSubmit')
								@submit.prevent="taxonomy.id ? update() : add()"
							@endslot
						
							<!--- Tag Name --->
							@input()
							
								@slot('label') name @endslot
								
								@slot('name') name @endslot
								
								@slot('placeholder') Tag name @endslot
								
								@slot('vModel') taxonomy.name @endslot
								
								@slot('vChange') taxonomy.errors.clear('name') @endslot
								
								@slot('help') @{{ taxonomy.name | strCount(70) }} @endslot
								
								@slot('vError') taxonomy @endslot
							
							@endinput
						
						
							<!--- Tag Slug --->
							@input(['disabled' => true])
							
								@slot('label') Slug @endslot
								
								@slot('name') Slug @endslot
								
								@slot('placeholder') Tag slug @endslot
								
								@slot('vModel') taxonomy.slug @endslot
								
								@slot('vChange') taxonomy.errors.clear('slug') @endslot
								
								@slot('help') @{{ taxonomy.slug | strCount(70) }} @endslot
								
								@slot('vError') taxonomy @endslot
								
								@slot('vIf') taxonomy.id @endslot
							
							@endinput
						
							<!--- Tag Description --->
							@textarea()
							
								@slot('label') Description @endslot
								
								@slot('name') description @endslot
								
								@slot('placeholder') Description @endslot
								
								@slot('vModel') taxonomy.description @endslot
								
								@slot('vChange') taxonomy.errors.clear('description') @endslot
								
								@slot('help') @{{ taxonomy.description | strCount(160) }} @endslot
								
								@slot('vError') taxonomy @endslot
							
							@endtextarea
							
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