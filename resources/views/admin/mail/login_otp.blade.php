@component('mail::message')
# Message From E-bank For Confirmation

OTP CODE: {{ $body['otp_number'] }}

Thanks,
{{ config('app.name') }}
@endcomponent

