@component('mail::message')
# Password Reset Request

Click the button below to reset your password. This link will expire in 60 minutes.

@component('mail::button', ['url' => route('password.reset', $token)])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
