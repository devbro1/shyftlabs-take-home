@component('mail::message')
# Introduction

Hello {{ $user->full_name }}

Please follow this link to reset your password:

@component('mail::button', ['url' => $url ])
Reset Password
@endcomponent

If you did not request this email, please ignore this message.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
