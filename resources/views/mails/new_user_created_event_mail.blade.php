@component('mail::message')
# hello {{ $name }}

Welcome to District10. Your profile has been created successfully. Please reset your password by password reset email.

@component('mail::button', ['url' => $tokenUrl])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent