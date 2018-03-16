@extends('layouts.app.master')

@section('header-class', 'bdB')

@section('content')

    <!--- Posts --->
    <section class="posts">

        <div class="container-fluid mt-5 pt-5 mb-5 pb-5">

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="heading-alpha txt-up text-center ls-15 brd-btm pb-2 h2 tt-u bdB">
                         <span class="c-gray">{{ count($icons) }}</span> <span class="c-gray-dark">Icons</span>
                    </h2>
                </div>
            </div>

            <div class="row pt-4">

                <div class="col-lg-12 d-f fxw-w">

                    @foreach($icons as $icon)

                        <a href="#" class="d-f fxw-w w-25 jc-c bd p-2 c-gray">

                            <p class="w-100 d-f jc-c">

                                @if( starts_with($icon, 'lp-logo') )
                                    <svg width="130" height="50">
                                        <use xlink:href="#{{ $icon }}"></use>
                                    </svg>
                                @else
                                    <svg width="50" height="50">
                                        <use xlink:href="#{{ $icon }}"></use>
                                    </svg>
                                @endif
                            </p>

                            <p class="fsz-lg">{{ $icon }}</p>
                        </a>

                    @endforeach
                </div>

            </div>

        </div>

    </section>


@endsection