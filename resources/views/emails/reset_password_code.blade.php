@component('mail::message')
# Password Reset Request

We received a request to reset your password. Use the code below to reset your password. The code is valid for the next 10 minutes.

@component('mail::panel')
### Your Reset Code: **{{ $code }}**
@endcomponent

If you did not request a password reset, please ignore this email or contact support if you have questions.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
