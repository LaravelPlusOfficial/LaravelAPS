@if($user->can('manage.acl'))

    @php($profileUserRoles = collect(array_keys($profileUserRoles)))

    <h2 class="fsz-sm tt-u bdB mb-3 pb-1 ls-12">Roles</h2>

    <div class="row">

        @foreach(collect($roles)->sortBy('label')->chunk(4) as $roles)

            <div class="col-lg-6">
                <ul class="list-unstyled m-0 p-0">
                    @foreach($roles as $role)

                        <li>
                            <label class="form-checkbox tt-n fsz-sm">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role['id']}}"
                                        {{ $profileUserRoles->intersect($role['slug'])->count() ? 'checked' : '' }}
                                >
                                <span>
                                    {{ ucwords($role['label']) }}
                                </span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

        @endforeach

    </div>

@else

    @if( collect($profileUserRoles)->count() )

        <h2 class="fsz-sm tt-u ls-12 bdB pb-1 mt-4">Your Roles</h2>

        <ul class="mb-4">

            @foreach($profileUserRoles as $rSlug => $rLabel)

                <li>{{ ucwords($rLabel) }}</li>

            @endforeach

        </ul>

    @endif

@endif