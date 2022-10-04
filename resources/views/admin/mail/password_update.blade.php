@component('mail::message')
# Message From E-bank For Confirmation

OTP CODE: {{ $value['confirmation_code'] }}

Thanks,
{{ config('app.name') }}
@endcomponent