@component('mail::message')
# {{ $body['subject'] }}

{{ $body['msg'] }}

Thanks,
{{ config('app.name') }}
@endcomponent