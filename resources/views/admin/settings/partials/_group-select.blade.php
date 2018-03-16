<div id="group-wrap" class="">
	
	<div id="group-select-wrap" class="d-f ai-fe mb-3">
		@select(['label' => 'Group', 'name' => 'group', 'class' => 'fxg-1 mb-0', 'required' => true])
		
		@slot('options')
			<option value="">Select setting group...</option>
			@foreach($groups as $group)
				<option value="{{ str_slug($group, '_') }}" value="{{ $group }}">{{ title_case($group) }}</option>
			@endforeach
		@endslot
		
		@endselect
		
		<button type="button" class="d-f btn btn-outline-info ml-2 group-form-toggle">New Group</button>
	</div>
	
	<div id="group-input-wrap" class="d-f ai-fe mb-3" style="display: none">
		
		@input([
			'label' => 'add_new_group',
			'name' => 'add_new_group',
			'placeholder' => 'Add new group',
			'class' => 'fxg-1 mb-0'
		]) @endinput
		
		<button type="button" class="d-f btn btn-outline-info ml-2" id="add-new-group">Add Group</button>
		
		<button type="button" class="d-f btn btn-outline-info ml-2 group-form-toggle">Cancel</button>
	
	</div>

</div>

@push('append-scripts')
	
	<script>
        $(document).ready(function () {
            
            let groupWrap = $($(admin).find('#group-wrap'));

            let groupSelectWrap = $($(groupWrap).find('#group-select-wrap'));

            let groupSelect = $($(groupWrap).find('select#group'));

            let groupInputWrap = $($(groupWrap).find('#group-input-wrap'));

            let inputNewGroup = $($(groupWrap).find('input#add-new-group'));

            let groupFormToggleButton = $($(groupWrap).find('button.group-form-toggle'));

            let addGroupButton = $($(groupWrap).find('button#add-new-group'));

            groupFormToggleButton.click(function (e) {
                if (groupWrap.hasClass('adding')) {
                    // reset
                    groupSelectWrap.show();
                    groupInputWrap.hide();
                    groupWrap.removeClass('adding')
                } else {
                    // show
                    groupSelectWrap.hide();
                    groupInputWrap.show();
                    groupWrap.addClass('adding')
                }
            });

            addGroupButton.click(function (e) {
                if (inputNewGroup.val()) {
                    let val = inputNewGroup.val();

                    groupSelect.append($('<option>',
                        {
                            value: val,
                            text: val
                        }));
                    
                    groupSelect.val(val);

                    groupSelectWrap.show();
                    groupInputWrap.hide();
                    groupWrap.removeClass('adding')
                }
            });


        });
	</script>

@endpush