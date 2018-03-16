@extends('layouts.admin.master')

@section('content')

    <div class="container-fluid" v-cloak>
    
        @pageTitle()
        Roles
        @endpageTitle

        <div class="row mb-5">

            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="bgc-red c-white p-3 mb-2">
                        <ul class="list-unstyled mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-lg-7">

                <div id="roles-collection">

                    @foreach($roles as $role)

                        <div class="card mb-3">

                            <div class="card-header d-f p-0">

                                <button class="btn btn-link btn-block ta-l p-3"
                                        data-toggle="collapse"
                                        data-target="#{{ $role['slug'] }}-role">
                                    <h5 class="mb-0 fsz-sm tt-u ls-12">{{ $role['label'] }}</h5>
                                </button>

                            </div>

                            <div id="{{ $role['slug']  }}-role"
                                 class="collapse {{ request()->input('role-show') == $role['id'] ? 'show' : '' }}"
                                 data-parent="#roles-collection">
                                <div class="card-body p-0">

                                    <form action="{{ route('admin.role.update', $role['id'] ) }}" method="POST">
                                        {{ method_field('PATCH') }}
                                        {{ csrf_field() }}

                                        @include('admin.roles._crud-form', ['role' => $role])

                                    </form>

                                </div>
                            </div>
                        </div>

                    @endforeach


                </div>

            </div>

            <div class="col-lg-5">

                <form action="{{ route('admin.role.store') }}" method="POST">
                    {{ csrf_field() }}

                    @include('admin.roles._crud-form',['role' => null])

                </form>

            </div>

        </div>

    </div>

@endsection