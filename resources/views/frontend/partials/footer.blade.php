@php
    use App\Models\Setting;
    use App\Models\LegalPage;

    $siteName = Setting::get('site_name', config('app.name', 'Quin Tech'));
    $wordmark = Setting::get('footer_wordmark') ?: $siteName;
    $logo     = Setting::get('logo');
    $logoH    = max((int) Setting::get('logo_height', 32), 30);
    $tagline  = Setting::get('footer_tagline', 'We design, build, and ship web platforms, mobile apps, and cloud systems for ambitious teams.');
    $email    = Setting::get('contact_email');
    $phone    = Setting::get('contact_phone');

    $socials = array_filter([
        'linkedin'  => Setting::get('social_linkedin'),
        'x'         => Setting::get('social_x'),
        'instagram' => Setting::get('social_instagram'),
        'facebook'  => Setting::get('social_facebook'),
    ]);

    try {
        $legal = LegalPage::where('is_active', true)->orderBy('title')->get();
    } catch (\Throwable $e) {
        $legal = collect();
    }
@endphp
<footer class="footer">
    <div class="wrap">
        <div class="footer-top">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="logo" aria-label="{{ $siteName }} home">
                    @if($logo)
                        <img src="{{ asset('storage/'.$logo) }}" alt="{{ $siteName }}" style="height:{{ $logoH }}px;width:auto;">
                    @else
                        {{ strtoupper($siteName) }}<span class="dot">.</span>
                    @endif
                </a>
                <p class="tagline">{{ $tagline }}</p>

                @if($email || $phone)
                    <div class="footer-contact">
                        @if($email)<a href="mailto:{{ $email }}">{{ $email }}</a>@endif
                        @if($phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>@endif
                    </div>
                @endif

                @if($socials)
                    <div class="footer-socials">
                        @foreach($socials as $net => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($net) }}">
                                @switch($net)
                                    @case('linkedin')
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM.24 8.02h4.48V24H.24V8.02zM8.34 8.02h4.29v2.18h.06c.6-1.13 2.06-2.32 4.24-2.32 4.53 0 5.37 2.98 5.37 6.86V24h-4.48v-6.37c0-1.52-.03-3.48-2.12-3.48-2.13 0-2.45 1.66-2.45 3.37V24H8.34V8.02z"/></svg>
                                        @break
                                    @case('x')
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2h3.3l-7.2 8.24L23.5 22h-6.6l-5.18-6.77L5.8 22H2.5l7.7-8.8L1.9 2h6.77l4.68 6.19L18.9 2zm-1.16 18h1.83L7.35 3.9H5.4L17.74 20z"/></svg>
                                        @break
                                    @case('instagram')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                                        @break
                                    @case('facebook')
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
                                        @break
                                @endswitch
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="footer-cols">
                <div class="footer-col">
                    <span class="head">Company</span>
                    <a href="{{ url('about') }}">About</a>
                    <a href="{{ route('team.index') }}">Team</a>
                    <a href="{{ route('contact.index') }}">Contact</a>
                </div>
                <div class="footer-col">
                    <span class="head">Explore</span>
                    <a href="{{ route('services.index') }}">Services</a>
                    <a href="{{ route('projects.index') }}">Projects</a>
                    <a href="{{ route('blog.index') }}">Blog</a>
                    <a href="{{ route('faq.index') }}">FAQ</a>
                </div>
                <div class="footer-col">
                    <span class="head">Legal</span>
                    @forelse($legal as $lp)
                        <a href="{{ route('legal.show', $lp->slug) }}">{{ $lp->title }}</a>
                    @empty
                        <a href="{{ route('contact.index') }}">Contact us</a>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <div class="footer-word">{{ $wordmark }}</div>
    </div>

    <div class="wrap">
        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</span>
            @if($legal->isNotEmpty())
                <div class="footer-legal">
                    @foreach($legal as $lp)
                        <a href="{{ route('legal.show', $lp->slug) }}">{{ $lp->title }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
