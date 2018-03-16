@extends('layouts.admin.master')

@section('content')

    <div class="container-fluid">
    
        @pageTitle(['icon' => 'icon-image'])
        Media
        @endpageTitle

        <!--- Media Library --->
        <div class="row">
            <div class="col-lg-12">
                @include('admin.media._media-library')
            </div>
        </div>

    </div>

@endsection