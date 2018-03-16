<modal name="{{ $id }}"
       :scrollable="true"
       height="auto">
	
	<div class="card" style="">
		
		<!--- Modal title --->
		<div class="card-header d-f jc-sb">
			
			<h4 class="fsz-md ls-12 tt-u mb-0">{{ title_case(str_replace('_', ' ', $setting->key)) }} - info</h4>
			
			<button type="button"
			        class="bg-n lh-1 fsz-sm bdr-5 bd-n cur-p fill-gray fill-primary-hv otl-n-fc"
			        @click="$modal.hide('{{ $setting->key }}-info')"
			        style="top: 36px; left: 10px">
				<vue-svg name="icon-x" square="20"></vue-svg>
			</button>
			
		</div>
		
		<!--- Info Details --->
		<ul class="list-group list-group-flush">
			
			@foreach($setting->info as $infoName => $infoVal)
				<li class="list-group-item">
					
					<p class="fsz-xs ls-11 tt-u mb-0 c-gray">{{ $infoName }}</p>
					<h5 class="fsz-md ls-11 m-0">
						
						@switch($infoName)
							
							@case('size')
								{{ humanFileSize($infoVal) }}
							@break
							
							@case('width')
							{{ $infoVal . 'px' }}
							@break
							
							@case('path')
							{{ $infoVal }}
							<span class="d-f fxd-c fxg-1">
								<span class="fsz-xs ls-11 tt-u mb-0 c-gray mt-2 ">Full path</span>
								<span class="fsz-sm ls-11">
									{{ asset($infoVal) }}
								</span>
							</span>
							@break
							
							@case('height')
							{{ $infoVal . 'px' }}
							@break
							
							@default
							{{ $infoVal }}
							
						@endswitch
						
					</h5>
					
				</li>
			@endforeach
		</ul>
	</div>

</modal>