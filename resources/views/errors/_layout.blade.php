@php
    // DB-safe: a 500 might be a database failure, so never let settings access break the page.
    try {
        $siteName = \App\Models\Setting::get('site_name', config('app.name', 'Quin Tech'));
        $logo = \App\Models\Setting::get('logo');
        $acc  = \App\Models\Setting::get('accent_color', '#ff6a00');
    } catch (\Throwable $e) {
        $siteName = config('app.name', 'Quin Tech');
        $logo = null;
        $acc = '#ff6a00';
    }
    $logoUrl = null;
    if ($logo) {
        try { $logoUrl = asset('storage/'.$logo); } catch (\Throwable $e) { $logoUrl = null; }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — @yield('heading', 'Error') · {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        :root{--acc:{{ $acc }};}
        body{min-height:100vh;background:#08080c;color:#f5f5f8;font-family:'Inter',sans-serif;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;position:relative;overflow:hidden;}
        .glow{position:absolute;top:-32%;left:50%;transform:translateX(-50%);width:900px;height:620px;max-width:130vw;background:radial-gradient(circle,color-mix(in srgb,var(--acc) 34%,transparent),transparent 62%);filter:blur(80px);opacity:.5;pointer-events:none;}
        .box{position:relative;max-width:540px;}
        .brand{font-family:'Space Grotesk';font-weight:700;font-size:19px;letter-spacing:.02em;margin-bottom:30px;}
        .brand .dot{color:var(--acc);}
        .brand img{height:32px;width:auto;}
        .code{font-family:'Space Grotesk';font-weight:700;font-size:clamp(88px,22vw,168px);line-height:.9;letter-spacing:-.03em;
            background:linear-gradient(180deg,#fff 0%,var(--acc) 160%);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;
            filter:drop-shadow(0 8px 40px color-mix(in srgb,var(--acc) 30%,transparent));}
        h1{font-family:'Space Grotesk';font-weight:600;font-size:clamp(22px,5vw,32px);margin-top:8px;}
        p{color:rgba(245,245,248,.62);font-size:16px;line-height:1.7;margin-top:14px;}
        .actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:30px;}
        .btn{display:inline-block;font-size:14px;font-weight:500;border-radius:11px;padding:12px 22px;cursor:pointer;text-decoration:none;transition:transform .15s,opacity .15s,border-color .2s;}
        .btn-acc{background:var(--acc);color:#1a1206;}
        .btn-acc:hover{opacity:.9;transform:translateY(-1px);}
        .btn-ghost{border:1px solid rgba(255,255,255,.18);color:#f5f5f8;}
        .btn-ghost:hover{border-color:rgba(255,255,255,.4);}
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="box">
        <div class="brand">
            @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $siteName }}">@else{{ strtoupper($siteName) }}<span class="dot">.</span>@endif
        </div>
        <div class="code">@yield('code')</div>
        <h1>@yield('heading', 'Something went wrong')</h1>
        <p>@yield('message', 'An unexpected error occurred.')</p>
        <div class="actions">
            @hasSection('actions')
                @yield('actions')
            @else
                <a href="{{ url('/') }}" class="btn btn-acc">← Back home</a>
            @endif
        </div>
    </div>
</body>
</html>
