@php $c = $section->content ?? []; @endphp
<section class="hero section" style="padding-bottom:clamp(40px,7vw,80px)">
    <div class="wrap">
        @if(!empty($c['badge_label']) || !empty($c['badge_text']))
            <span class="pill glass">
                @if(!empty($c['badge_label']))<span class="tag">{{ $c['badge_label'] }}</span>@endif
                {{ $c['badge_text'] ?? '' }}
                @include('frontend.partials.icon', ['name' => 'chevron'])
            </span>
        @endif

        <h1>{{ $c['heading'] ?? 'Software solutions, powered by engineering.' }}</h1>

        @if(!empty($c['subheading']))
            <p class="lead">{{ $c['subheading'] }}</p>
        @endif

        <div class="hero-btns">
            @if(!empty($c['secondary_label']))
                <a href="{{ $c['secondary_url'] ?? '#' }}" class="btn glass">
                    {{ $c['secondary_label'] }} @include('frontend.partials.icon', ['name' => 'arrow'])
                </a>
            @endif
            @if(!empty($c['primary_label']))
                <a href="{{ $c['primary_url'] ?? route('contact.index') }}" class="btn btn-acc">
                    {{ $c['primary_label'] }} @include('frontend.partials.icon', ['name' => 'arrow'])
                </a>
            @endif
        </div>
    </div>

    <div class="stage">
        <div class="sun-wrap">
            <div class="sun-glow"></div>
            <div class="sun-ring"></div>
        </div>
        <div class="wrap hero-visual">
            @if(!empty($c['image']))
                <img src="{{ asset('storage/'.$c['image']) }}" alt="{{ $c['heading'] ?? 'Showcase' }}">
            @else
                {{-- Default hero image: a styled dashboard mock (replaceable by an admin upload) --}}
                <div class="dash glass">
                    <div class="dash-top">
                        <span class="logo" style="font-size:13px">QUIN TECH<span class="dot">.</span></span>
                        <div class="dash-nav">
                            <span class="on">Analytics</span><span>Products</span><span>Customers</span><span>Campaigns</span>
                        </div>
                        <span style="width:20px;height:20px;border-radius:50%;background:var(--acc-grad)"></span>
                    </div>
                    <div class="dash-head">
                        <h3 style="font-size:17px">Dashboard</h3>
                        <span class="btn glass" style="font-size:11px;padding:7px 12px">Invite users</span>
                    </div>
                    <div class="metrics">
                        <div class="metric"><div class="k">TOTAL USERS</div><div class="v">72,350</div><span class="up">▲ 6.9%</span></div>
                        <div class="metric"><div class="k">SESSIONS</div><div class="v">29.4%</div><span class="down">▼ 12.5%</span></div>
                        <div class="metric"><div class="k">CLICK RATE</div><div class="v">56.8%</div><span class="up">▲ 19.3%</span></div>
                        <div class="metric"><div class="k">PAGEVIEWS</div><div class="v">92,913</div><span class="down">▼ 12.5%</span></div>
                    </div>
                    <p class="dash-note">This dashboard is an admin-uploaded hero image — swap it for any showcase graphic.</p>
                </div>
            @endif
        </div>
    </div>
</section>
