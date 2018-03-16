@php($role = isset($role) ? $role : null)

@php($editable = optional($role)->editable ?? true )

<div class="card">

    <div class="card-body">

        @if(!$role)
            <h2 class="fsz-sm tt-u ls-12 bdB pb-1">Add Role</h2>
        @endif

    <!--- Label --->
        <div class="form-group">

            <label for="label">Label</label>

            <input type="text"
                   name="label"
                   id="label"
                   class="form-control"
                   {{ !$editable ? 'disabled' : ''  }}
                   value="{{ old('label') ?? isset($role) ? $role['label'] : '' }}" placeholder="Role label...">

        </div>

        @if($role)
        <!--- Slug --->
            <div class="form-group">
                <label for="label">Slug</label>
                <input type="text" name="label" id="label" class="form-control" disabled value="{{ $role['slug'] }}">
            </div>

        @endif

        <h4 class="bdB h5 fsz-md ls-12 tt-u d-f">
            <span class="d-f">Permissions</span>

            @if($editable)
                <button type="button"
                        {{ ! $editable ? 'disabled' : ''  }}
                        onclick="for(c in document.getElementsByClassName('permissions-checkbox-{{ $role ? $role['id'] : '' }}') ) document.getElementsByClassName('permissions-checkbox-{{ $role ? $role['id'] : '' }}').item(c).checked = true"
                        class="bg-n bd-n p-0 lh-1 ml-3 fsz-xs ls-12 tt-u c-gray otl-n-fc c-primary-hv cur-p">
                    Check all
                </button>

                <button type="button"
                        {{ ! $editable ? 'disabled' : ''  }}
                        onclick="for(c in document.getElementsByClassName('permissions-checkbox-{{ $role ? $role['id'] : '' }}') ) document.getElementsByClassName('permissions-checkbox-{{ $role ? $role['id'] : '' }}').item(c).checked = false"
                        class="bg-n bd-n p-0 lh-1 ml-3 fsz-xs ls-12 tt-u c-gray otl-n-fc c-primary-hv cur-p">
                    UnCheck all
                </button>
            @endif

        </h4>

        <div class="row pl-0 pr-0 pt-2 mt-2 mxh-1000 ovf-y-a">

            @foreach($permissions as $letter => $permissionsCollection)

                <div class="col-lg-12">
                    <ul class="list-unstyled pt-1 pl-2 pr-2">
                        @foreach($permissionsCollection as $permission)
                            <li>

                                @if($editable)
                                    <label class="form-checkbox tt-n fsz-sm">
                                        <input type="checkbox"
                                               class="permissions-checkbox-{{ optional($role)->id ?? '' }}"
                                               name="permissions[]"
                                               {{ ($role && $acl->roleHasPermission($role, $permission['slug']) ) ? 'checked' : '' }}
                                               value="{{ $permission['id'] }}">
                                        <span>{{ ucwords($permission['label']) }}</span>
                                    </label>
                                @else
                                    {{ ucwords($permission['label']) }}
                                @endif

                            </li>
                        @endforeach
                    </ul>

                </div>

            @endforeach

        </div>

        <div class="d-f jc-sb">
            <button type="submit"
                    {{ ! $editable ? 'disabled' : '' }}
                    class="btn btn-primary tt-u fsz-sm ls-12">
                {{ isset($role) ? 'Update' : 'Create' }}
            </button>
        </div>

    </div>

</div>