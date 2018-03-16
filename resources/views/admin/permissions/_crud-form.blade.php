@php($permission = isset($permission) ? $permission : null)

<div class="card">

    <div class="card-body">

        <h2 class="fsz-sm tt-u ls-12 bdB pb-1">Add Permission</h2>

        @if ($errors->any())
            <div class="bgc-red c-white p-3 mb-2">
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <!--- Label --->
        <div class="form-group">

            <label for="label">Label</label>

            <input type="text" name="label" id="label" class="form-control"
                   value="{{ old('label') ?? '' }}" placeholder="Permission label...">

            <p class="fs-i fsz-sm mt-1 c-gray ls-11">Created permission can not be edited, It can only be deleted</p>

        </div>

        <button type="submit" class="btn btn-primary tt-u fsz-sm ls-12">Create</button>

    </div>

</div>