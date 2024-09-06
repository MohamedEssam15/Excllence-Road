@component('mail::message')
# Verify Your Email Address

Please use the following code to verify your email address. The code is valid for 10 minutes.

@component('mail::panel')
### Your Verification Code: **{{ $code }}**
@endcomponent

If you did not request this verification, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
