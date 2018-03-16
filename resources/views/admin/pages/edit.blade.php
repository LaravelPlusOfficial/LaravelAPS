@extends('layouts.admin.master')

@section('content')

    @include('admin.posts._partials.manage-post', [
        'post' => $page,
        'postUrl' => route('admin.page.update', $page->id),
        'postType' => 'page'
    ])

@endsection