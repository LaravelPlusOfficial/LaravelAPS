<div class="d-b w-100 mb-4">
	
	<!--- Form start --->
	@form(['method' => 'post', 'action' => route('admin.setting.update'), 'update' => true, 'file' => true])
	
	@php($id = 'group_' . str_slug($group) )
	@php($hideUpdateButton = false )
	
	<div class="card bg-light">
		
		<!--- Group Heading --->
		<div class="card-header d-f ac-c ai-c p-0">
			
			<button data-toggle="collapse"
			        data-target="#{{ $id }}"
			        type="button"
			        class="btn btn-link btn-block ta-l p-3 collapsed">
				
				<h5 class="mb-0 fsz-sm tt-u ls-12">{{ str_replace('_', ' ', $group) }}</h5>
			
			</button>
		
		</div>
		
		<!--- Group Settings --->
		<div id="{{ $id }}" data-parent="#settings-collection" class="collapse">
			
			<div class="card-body">
				
				<!--- Settings loop --->
				@foreach($settings as $setting)
					
					@if(view()->exists("components.forms.{$setting->type}"))
						
						<div class="p-rel">
						
						@php($keyInfo = " <span class='tt-n'>{$setting->key}</span>")
						
						@component('components.forms.' . $setting->type, [
							'group' => $group,
							'label' => $setting->label,
							'name' => $setting->key,
							'value' => $setting->value,
							'help' => optional($setting)->help,
							'helpLabel' => optional($setting)->helpLabel . $keyInfo,
							'maxWidth' => '300px',
							'info' => $setting->info ? true: false,
							'modalId' => "{$setting->key}-info",
							'key' => "Key - {$setting->key}",
							'setting' => $setting->toJson(),
							'disabled' => $setting->disabled
						])
							
							<!--- Image info preview button if image--->
								@if($setting->type == 'image')
									@slot('buttonSlot')
										<button class="bg-n p-0 m-0 lh-1 bd-n fill-gray fill-gray-dark-hv cur-p o-n-fc ml-2 otl-n-fc"
										        type="button"
										        v-if="props.file"
										        slot-scope="props"
										        @click="$modal.show('{{ $setting->key }}-info')"
										        slot="buttons">
											<vue-svg name="icon-eye" square="20"></vue-svg>
										</button>
									@endslot
								@endif
							
							<!--- Select Option --->
								@if($setting->type == 'select')
									
									@slot('options')
										@foreach($setting->options as $optionLabel => $optionValue)
											
											@php($selected = $optionValue == $setting->value ? 'selected' : null)
											
											<option value="{{ $optionValue }}" {{ $selected }}>
												{{ title_case($optionLabel) }}
											</option>
										
										@endforeach
									@endslot
									
									@if($setting->group == 'social_auto_post')
										
										@php($hideUpdateButton = true)
										
										@slot('vChange')
											toggleSocialAutoPostSetting(
											'{{ $setting->group }}',
											'{{ $setting->key }}',
											$event.target.value,
											'{{ route('social.auto.publish.enable.provider', '%provider%') }}',
											'{{ route('social.auto.publish.disable.provider', '%provider%') }}',
											)
										@endslot
									
									@endif
								
								@endif
							
							@endcomponent
							
							@if($setting->info)
								
								@include('admin.settings.partials._info-modal', [
									'id' => "{$setting->key}-info",
									'setting' => $setting
								])
							
							@endif
						
						
						</div>
					
					@else
						
						<hr>
						<p class="c-red">
							No Component for
							<strong>{{ strtoupper($setting->type) }}</strong>
						</p>
					@endif
				
				@endforeach
			
			</div>
		
		@if(!$hideUpdateButton)
			
			<!--- Settings Update Button --->
				<div class="card-footer">
					
					@button(['type' => 'submit'])
					Update
					@endbutton
				
				</div>
			@endif
		
		</div>
	
	</div>
	
	@endform

</div>