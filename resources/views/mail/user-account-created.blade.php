<x-mail::message>
Dear {{$user->fullname}}

An account has been created for you in {{ config('app.name') }} by {{ $creator->fullname }}.

You can set your password and login using the following link:

<x-mail::button :url="$loginUrl">
Set Password
</x-mail::button>

*The above button will expire in 24 hours.*

*If the above link does not work, paste this URL directly into your browser's location field*

**{{ $loginUrl }}**

Thanks,<br>
{{ config('app.name') }} Admin
</x-mail::message>
