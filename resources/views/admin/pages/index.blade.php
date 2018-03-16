@extends('layouts.admin.master')

@section('content')

    <div class="container-fluid" v-cloak>

        <div class="row">

            <div class="col-lg-12">
                <h1 class="txt-6 fsz-md tt-u ls-12">Pages
                    <small>({{ $pages->total() }})</small>
                </h1>
            </div>

        </div>

        <div class="row">

            <div class="col-lg-12 mb-5">

                @if(empty($pages->items()))

                    <div class="pt-5">
                        <div class="d-f jc-c ai-c bgc-gray-light p-5 bdr-10 mt-5">
                            <p class="mb-0 mr-2">There is no page to show.</p>
                            <a href="{{ route('admin.page.create') }}" class="btn btn-primary btn-sm fsz-sm tt-u ls-12">Add
                                Page</a>
                        </div>
                    </div>

                @else

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th scope="col"><span class="fsz-xs tt-u ls-12">#</span></th>
                                <th scope="col"><span class="fsz-xs tt-u ls-12">Title</span></th>
                                <th scope="col"><span class="fsz-xs tt-u ls-12">Published On</span></th>
                                <th scope="col"><span class="fsz-xs tt-u ls-12">Created On</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = ($pages->currentpage()-1)* $pages->perpage())

                            @foreach($pages as $page)

                                <tr>
                                    <td>
                                        {{ ++$i }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.page.edit', $page->id) }}">
                                            {{ $page->title }}
                                        </a>
                                        <p class="mb-0">
                                            {{ $page->author->name }}

                                            @if($page->status == 'published')
                                                <span class="badge badge-success fsz-xxs tt-u ls-12">Published</span>
                                            @else
                                                <span class="badge badge-secondary fsz-xxs tt-u ls-12">draft</span>
                                            @endif

                                            {{ $page->post_type }}

                                        </p>
                                    </td>
                                    <td>{{ $page->publish_at ? $page->publish_at->diffForHumans() : '-'}}</td>
                                    <td>{{ $page->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pages->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection