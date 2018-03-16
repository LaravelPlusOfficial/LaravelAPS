@extends('layouts.auth.master')

@section('app-class', 'grad-atlas ai-c')

@section('content')

    <div class="auth d-flex flex-column mt-5">

        <div class="d-flex jc-c ai-c ac-c mb-3">

            <a href="/" class="d-flex jc-c">
                <svg width="250" height="50">
                    <use xlink:href="#lp-logo-full-white"></use>
                </svg>
            </a>

            <h1 class="d-f fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2">
                Confirm Email
            </h1>
        </div>

        <form method="POST" action="{{ route('email.confirmation.create') }}" class="d-flex jc-c ai-c fxd-c mt-3">
        {{ csrf_field() }}

            <!--- Email --->
            <label for="" class="fsz-xs tt-u c-white ls-12">Enter email to resend confirmation code</label>
            <input type="email" name="email" placeholder="Email..." class="input-transparent w-100 mw-90">

            @if ($errors->has('email'))
                <p class="mb-0">{{ $errors->first('email') }}</p>
            @endif

            @if (session('message'))
                <p class="mb-0">{{ session('message') }}</p>
            @endif

            <div class="w-100 mw-90 mt-4 d-flex ai-c justify-content-between">

                <button class="btn btn-primary fsz-sm tt-u ls-12 fw-600" type="submit">Resend</button>

                <div class="d-flex">
                    <a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
                    <span class="text-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
                    <a href="{{ route('register') }}" class="text-white fsz-sm tt-u ls-12 fw-600">Sign up</a>
                </div>

            </div>

        </form>

    </div>


@endsection