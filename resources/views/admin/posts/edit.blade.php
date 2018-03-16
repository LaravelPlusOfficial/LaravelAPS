@extends('layouts.admin.master')

@section('content')

    @include('admin.posts._partials.manage-post', [
        'post' => $post,
        'postUrl' => route('admin.post.update', $post->id)
    ])

@endsection