@extends('layouts.auth.master')

@section('app-class', 'grad-atlas align-items-center')

@section('content')

    <div class="d-f ac-c ai-c mt-5 pt-5 c-white fill-white">

        <div class="d-f ac-c ai-c mt-5 pt-5">
            <vue-svg name="icon-lock" square="60"></vue-svg>
            <h1 class="d-f m-3">403</h1>
        </div>

    </div>

    <p class="mb-0 fsz-lg tt-u c-white ls-14 mt-5 fw-300">Access is <span class="fw-600 ls-16">unauthorized</span></p>

@endsection

