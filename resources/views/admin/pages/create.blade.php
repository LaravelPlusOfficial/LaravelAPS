@extends('layouts.admin.master')

@section('content')

    @include('admin.posts._partials.manage-post', [
        'postUrl' => route('admin.page.index'),
        'postType' => 'page'
    ])

@endsection