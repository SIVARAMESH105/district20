@component('mail::message')
# Hello,

District10 contact form submitted. Please find below info.
@component('mail::table')
|               |               |
| ------------- |:-------------:|
| Name          | {{ $name }}   |
| Email         | {{ $email }}  |
| Message       | {{ $message }}|
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent