@php use App\Models\Setting; $siteName = Setting::get('site_name', config('app.name', 'Quin Tech')); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in — {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>:root{ --acc: {{ Setting::get('accent_color', '#ff6a00') }}; }</style>
</head>
<body>
    <div class="login-wrap">
        <form class="login-card" method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <div class="login-logo">{{ strtoupper($siteName) }}<span class="dot">.</span></div>
            <p class="login-sub">Admin sign in</p>

            @if ($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div class="row-between">
                <label class="check">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-acc">Sign in</button>
        </form>
    </div>
</body>
</html>
