@php

    $flashLevel = session('error') ? 'error' : 'success';
    $flashLevel = session('warning') ? 'warning' : $flashLevel;
    $flashLevel = session('info') ? 'info' : $flashLevel;

    $flashMessage = session('error') ? session('error') : session('success');
    $flashMessage = session('warning') ? session('warning') : $flashMessage;
    $flashMessage = session('info') ? session('info') : $flashMessage;

@endphp
<flash data-level="{{ $flashLevel }}" data-message="{{ $flashMessage }}"></flash>