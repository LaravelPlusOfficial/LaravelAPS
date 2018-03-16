@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')

    <div class="auth d-flex flex-column mt-5">

        <div class="d-flex justify-content-center align-items-center align-content-center mb-3">

            <a href="/" class="d-flex justify-content-center">
                <svg width="250" height="50">
                    <use xlink:href="#lp-logo-full-white"></use>
                </svg>
            </a>

            <h1 class="d-flex align-self-center fs-14 bold ls-15 txt-up text-center text-white mb-0 ml-3 pl-3 brd-left-white lh-2">
                Resend Code
            </h1>
        </div>

        @if ($errors->any())
            <ul class="bgc-pink list-unstyled p-2">
                @foreach ($errors->all() as $error)
                    <li class="c-white">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('email.verification.create') }}" class="d-flex justify-content-center align-items-center flex-column mt-3">
            {{ csrf_field() }}
            <!--- Email --->
            <label for="" class="fs-12 ls-12 w-100 mw-90 txt-up text-white">Enter email to Resend Verification Code</label>
            <input type="email" name="email" placeholder="Email..." class="input-transparent w-100 mw-90">

            <div class="w-100 mw-90 mt-4 d-flex align-items-center justify-content-between">

                <button class="btn btn-primary fs-14 ls-12 txt-up bold" type="submit">Resend</button>

                <div class="d-flex">
                    <a href="{{ route('login') }}" class="text-white fs-12 ls-12 txt-up bold mr-3">Login</a>
                    <span class="text-white fs-12 ls-12 txt-up bold mr-3">|</span>
                    <a href="{{ route('register') }}" class="text-white fs-12 ls-12 txt-up bold">Sign up</a>
                </div>

            </div>

        </form>

    </div>


@endsection