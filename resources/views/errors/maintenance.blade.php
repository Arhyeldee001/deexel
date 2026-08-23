@php use App\Models\Setting; $siteName = Setting::get('site_name', config('app.name', 'Quin Tech')); $logo = Setting::get('logo'); $acc = Setting::get('accent_color', '#ff6a00'); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading ?? 'Maintenance' }} — {{ $siteName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        :root{--acc:{{ $acc }};}
        body{min-height:100vh;background:#08080c;color:#f5f5f8;font-family:'Inter',sans-serif;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;overflow:hidden;position:relative;}
        .glow{position:absolute;top:-30%;left:50%;transform:translateX(-50%);width:900px;height:600px;background:radial-gradient(circle,color-mix(in srgb,var(--acc) 35%,transparent),transparent 60%);filter:blur(80px);opacity:.5;pointer-events:none;}
        .box{position:relative;max-width:520px;}
        .brand{font-family:'Space Grotesk';font-weight:700;font-size:20px;letter-spacing:.02em;margin-bottom:36px;}
        .brand .dot{color:var(--acc);}
        .brand img{height:34px;width:auto;}
        .tag{display:inline-block;font-size:12px;text-transform:uppercase;letter-spacing:.14em;color:var(--acc);border:1px solid color-mix(in srgb,var(--acc) 40%,transparent);border-radius:999px;padding:6px 14px;margin-bottom:22px;}
        h1{font-family:'Space Grotesk';font-weight:700;font-size:clamp(30px,7vw,52px);line-height:1.05;}
        p{color:rgba(245,245,248,.66);font-size:16px;line-height:1.7;margin-top:16px;}
        .dots{display:flex;gap:8px;justify-content:center;margin-top:34px;}
        .dots span{width:9px;height:9px;border-radius:50%;background:var(--acc);opacity:.3;animation:pulse 1.4s infinite;}
        .dots span:nth-child(2){animation-delay:.2s;}
        .dots span:nth-child(3){animation-delay:.4s;}
        @keyframes pulse{0%,100%{opacity:.25;transform:scale(1);}50%{opacity:1;transform:scale(1.3);}}
    </style>
</head>
<body>
    <div class="glow"></div>
    <div class="box">
        <div class="brand">
            @if($logo)<img src="{{ asset('storage/'.$logo) }}" alt="{{ $siteName }}">@else{{ strtoupper($siteName) }}<span class="dot">.</span>@endif
        </div>
        <span class="tag">Maintenance</span>
        <h1>{{ $heading ?? 'We’ll be right back' }}</h1>
        <p>{{ $message ?? 'Our site is undergoing scheduled maintenance. Please check back shortly.' }}</p>
        <div class="dots"><span></span><span></span><span></span></div>
    </div>
</body>
</html>
