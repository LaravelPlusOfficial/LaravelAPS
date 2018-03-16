@component('mail::message')
# Inquiry from {{ $senderName }} - {{ $senderEmail }}

{{ nl2br($inquiry) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
