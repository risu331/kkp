<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Reset Password</h2>
        <p>Hallo {{ $user->username ?? '' }}!</p>
        <p>Untuk mengatur ulang password, silakan klik tautan berikut : </p>
        <a href="{{ route('forget-password.redirect') }}?token={{ $token }}&id={{ $user->id }}">{{ route('forget-password.redirect') }}?token={{ $token }}&id={{ $user->id }}</a>
    </body>
</html>