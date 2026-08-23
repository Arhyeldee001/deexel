@php
    use App\Models\Setting;
    $siteName = Setting::get('site_name', config('app.name', 'Quin Tech'));
    $logo     = Setting::get('logo');
    $logoH    = (int) Setting::get('logo_height', 32);
    $ctaLabel = Setting::get('nav_cta_label', 'Get a quote');
@endphp
<header class="nav">
    <div class="wrap nav-inner">
        <a href="{{ route('home') }}" class="logo" aria-label="{{ $siteName }} home">
            @if($logo)
                <img src="{{ asset('storage/'.$logo) }}" alt="{{ $siteName }}" style="height:{{ $logoH }}px;width:auto;">
            @else
                {{ strtoupper($siteName) }}<span class="dot">.</span>
            @endif
        </a>

        <nav>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                @isset($navPages)
                    @foreach($navPages as $p)
                        @continue($p->slug === 'home')
                        <li><a href="{{ url($p->slug) }}" class="{{ request()->is($p->slug) || request()->is($p->slug.'/*') ? 'active' : '' }}">{{ $p->title }}</a></li>
                    @endforeach
                @endisset
            </ul>
        </nav>

        <div class="nav-cta">
            <a href="{{ route('contact.index') }}" class="btn btn-acc btn-quote">
                {{ $ctaLabel }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
            <button class="nav-toggle" aria-label="Toggle menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="width:26px;height:26px"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
            </button>
        </div>
    </div>
</header>
