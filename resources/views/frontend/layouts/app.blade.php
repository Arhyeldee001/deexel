@php
    use App\Models\Setting;
    use App\Models\Page;

    $siteName = Setting::get('site_name', config('app.name', 'Quin Tech'));
    $navPages = Page::where('is_active', true)->where('show_in_nav', true)
                    ->orderBy('sort_order')->get();

    $pageTitle = $metaTitle ?? ($title ?? null);
    $fullTitle = $pageTitle ? ($pageTitle.' — '.$siteName) : $siteName;

    $accType  = Setting::get('accent_type', 'solid');
    $acc1     = Setting::get('accent_color', '#ff6a00');
    $acc2     = Setting::get('accent_color_2', $acc1);
    $accAngle = (int) Setting::get('accent_angle', 90);
    $accGrad  = $accType === 'gradient' ? "linear-gradient({$accAngle}deg, {$acc1}, {$acc2})" : $acc1;

    $logo    = Setting::get('logo');
    $favicon = Setting::get('favicon');
    $ogImageSetting = Setting::get('og_image');
    $ogImageUrl = !empty($ogImage) ? $ogImage : ($ogImageSetting ? asset('storage/'.$ogImageSetting) : null);
    $ga = Setting::get('google_analytics_id');

    $orgSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => $siteName,
        'url'      => url('/'),
        'logo'     => $logo ? asset('storage/'.$logo) : null,
        'sameAs'   => array_values(array_filter([
            Setting::get('social_linkedin'), Setting::get('social_x'),
            Setting::get('social_instagram'), Setting::get('social_facebook'),
        ])),
    ], fn ($v) => $v !== null && $v !== []);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription ?? Setting::get('meta_description', '') }}">
    @if(!empty($metaKeywords))<meta name="keywords" content="{{ $metaKeywords }}">@endif
    <link rel="canonical" href="{{ url()->current() }}">

    @if($favicon)<link rel="icon" href="{{ asset('storage/'.$favicon) }}">@endif

    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDescription ?? Setting::get('meta_description', '') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImageUrl)<meta property="og:image" content="{{ $ogImageUrl }}">@endif
    <meta name="twitter:card" content="{{ $ogImageUrl ? 'summary_large_image' : 'summary' }}">

    <script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if(!empty($jsonLd))
        <script type="application/ld+json">{!! json_encode(array_filter($jsonLd), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    <style>
        :root{ --acc: {{ $acc1 }}; --acc-2: {{ $acc2 }}; --acc-grad: {{ $accGrad }}; }
        .btn-acc, .btn.btn-acc{ background: var(--acc-grad); }
    </style>

    @if($ga)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga }}');
        </script>
    @endif
</head>
<body>
    @include('frontend.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    {{-- Pre-tag reveal targets before paint so they don't flash in --}}
    <script>
        (function () {
            document.documentElement.classList.add('js');
            var secs = document.querySelectorAll('main section:not(.hero)');
            for (var i = 0; i < secs.length; i++) {
                var sec = secs[i];
                if (sec.querySelector('.reveal')) continue;
                var wrap = sec.querySelector(':scope > .wrap');
                var blocks = wrap ? wrap.children : null;
                if (blocks && blocks.length) {
                    for (var j = 0; j < blocks.length; j++) blocks[j].classList.add('reveal');
                } else {
                    sec.classList.add('reveal');
                }
            }
        })();
    </script>

    {{-- Motion libraries (self-host these for production on TrueHost) --}}
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/lenis@1.1.14/dist/lenis.min.js"></script>
    <script defer src="{{ asset('js/frontend.js') }}"></script>
</body>
</html>
