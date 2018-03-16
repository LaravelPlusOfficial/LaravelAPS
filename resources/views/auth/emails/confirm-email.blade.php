@extends('layouts.auth.master')

@section('app-class', 'grad-atlas ai-c')

@section('content')

    <div class="d-flex flex-column mt-5 mw-90">

        <div class="d-flex jc-c ai-c ac-c mb-3">

            <a href="/" class="d-flex jc-c">
                <svg width="250" height="50">
                    <use xlink:href="#lp-logo-full-white"></use>
                </svg>
            </a>

            <h1 class="d-f fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2">
                Thank you
            </h1>
        </div>

        <p class="mt-2 fsz-md fw-300 c-white ta-c tt-u">Thank you <span class="fw-500 ls-12">registering</span></p>
        <p class="tt-u ta-c fsz-lg c-white fw-300">Please Check your email to <span class="fw-500 ls-12">confirm</span></p>


        <div class="d-flex jc-c mt-4">
            <a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
            <span class="text-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
            <a href="{{ route('register') }}" class="text-white fsz-sm tt-u ls-12 fw-600">Sign up</a>
        </div>

    </div>


@endsection