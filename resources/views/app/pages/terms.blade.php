@extends('layouts.app.master')


@section('content')

    <main class="main">

        <!--- Title --->
        <section class="posts pb-5">
    
            @include('app.pages._partials._title', ['title' => $page->title])

            <div class="container-fluid mt-5 mb-5">

                <div class="row pt-4">

                    <div class="col-lg-12">

                        {!! $page->body !!}

                    </div>

                </div>

            </div>

        </section>

@endsection