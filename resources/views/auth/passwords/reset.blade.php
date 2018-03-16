@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')

    <div class="auth d-flex flex-column mt-5">

        <div class="d-flex justify-content-center align-items-center align-content-center">

            <a href="/" class="d-flex justify-content-center">
                <svg width="250" height="50">
                    <use xlink:href="#lp-logo-full-white"></use>
                </svg>
            </a>

            <h1 class="d-flex mb-0 ml-3 pl-3 fsz-sm fw-600 tt-u c-white ls-12 mb-0 ml-3 pl-3 lh-2 bdL bdw-2">
                New Password
            </h1>
        </div>

        @if ($errors->any())
            <ul class="bgc-pink list-unstyled p-2">
                @foreach ($errors->all() as $error)
                    <li class="c-white">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('password.request') }}" class="d-flex justify-content-center align-items-center flex-column mt-3">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <!--- Email --->
            <label for="" class="fsz-xs tt-u c-white ls-12">Email</label>
            <input type="email" name="email" placeholder="Email..." class="input-transparent w-100 mw-90">

            <!--- Password --->
            <label for="password" class="fsz-xs tt-u c-white ls-12 mt-4">New Password</label>
            <input type="password" name="password" placeholder="New password..." class="input-transparent w-100 mw-90">

            <!--- Confirm Password --->
            <label for="password_confirmation" class="fsz-xs tt-u c-white ls-12 mt-4">Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm password..." class="input-transparent w-100 mw-90">

            <div class="w-100 mw-90 mt-4 d-flex align-items-center justify-content-between">

                <button class="btn btn-primary fsz-sm tt-u ls-12 fw-600" type="submit">Reset</button>

                <div class="d-flex">
                    <a href="{{ route('login') }}" class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">Login</a>
                    <span class="c-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
                    <a href="{{ route('register') }}" class="c-white fsz-sm tt-u ls-12 fw-600">Sign up</a>
                </div>

            </div>

        </form>

    </div>


@endsection