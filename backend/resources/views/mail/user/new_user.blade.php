@component('mail::message')
# Introduction

Welcome to Meow System.

Please follow this link to set your password and start using the system:

@component('mail::button', ['url' => $verify_email_url ])
Set your New Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
