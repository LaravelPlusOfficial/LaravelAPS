@extends('layouts.admin.master')

@section('content')

    <div class="container-fluid" v-cloak>
    
        @pageTitle()
        Permissions
        @endpageTitle

        <div class="row mb-5">

            <div class="col-lg-7">

                <div id="roles-collection">

                    @foreach($permissions as $perm)

                        <div class="card mb-2">
                            <div class="card-header d-f ac-c ai-c p-0">
                                <button class="btn btn-link btn-block ta-l p-2"
                                        data-toggle="collapse"
                                        data-target="#{{ $perm['slug'] }}-role">
                                    <h5 class="mb-0 fsz-sm tt-u ls-12">{{ $perm['label'] }}</h5>
                                </button>

                                <form id="permission-{{ $perm['id'] }}"
                                      action="{{ route('admin.permission.destroy', $perm['id']) }}"
                                      @submit.prevent="$confirm('Are you sure you want to delete this permission').then(() => $event.target.submit() )"
                                      method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}

                                    <button type="submit"
                                            class="p-0 m-0 lh-1 fill-gray fill-primary-hv bg-n bd-n mr-2 mt-1 otl-n-fc">
                                        <vue-svg name="icon-delete" square="20"></vue-svg>
                                    </button>

                                </form>

                            </div>

                            <div id="{{ $perm['slug']  }}-role"
                                 class="collapse"
                                 data-parent="#roles-collection">
                                <div class="card-body p-0">

                                    @if($perm['roles'])
                                        <ul class="list-unstyled mb-0 p-3">
                                            @foreach($perm['roles'] as $role)

                                                <li>{{ ucwords($role['label']) }}</li>

                                            @endforeach
                                        </ul>
                                    @else

                                        <p class="p-3 mb-0">No role attached</p>

                                    @endif

                                </div>
                            </div>
                        </div>

                    @endforeach


                </div>

            </div>

            <div class="col-lg-5">

                <form id="add-new-permission" action="{{ route('admin.permission.store') }}" method="POST">
                    {{ csrf_field() }}

                    @include('admin.permissions._crud-form')

                </form>
            </div>

        </div>

    </div>

@endsection