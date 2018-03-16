@php($profileUserPermissions = collect($profileUserPermissions))

@if( $profileUserPermissions->count() )

    <h2 class="fsz-sm tt-u ls-12 bdB pb-1 mt-4">Your have following Permissions</h2>

    <ul class="mb-4">

        @foreach($profileUserPermissions as $permission)

            <li>{{ ucwords($permission['label']) }}</li>

        @endforeach

    </ul>

@endif