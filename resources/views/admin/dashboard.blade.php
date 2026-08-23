@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $hour = (int) now()->format('H');
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $first = explode(' ', trim(auth()->user()?->name ?? ''))[0] ?? '';
    @endphp

    <div class="dash-head">
        <div>
            <h2 class="dash-hi">{{ $greeting }}{{ $first ? ', '.$first : '' }}.</h2>
            <p class="dash-date">{{ now()->format('l, j F Y') }}</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn-sm ghost">View site ↗</a>
    </div>

    @if($maintenance)
        <div class="dash-maint">
            <div><strong>Maintenance mode is ON.</strong> Visitors see the maintenance page; you and other admins can still browse normally.</div>
            @if($canSettings)<a href="{{ route('admin.settings.edit') }}" class="btn-sm">Turn off →</a>@endif
        </div>
    @endif

    @if(count($stats))
        <div class="dash-cards">
            @foreach($stats as $s)
                <a href="{{ $s['url'] }}" class="dash-card {{ !empty($s['alert']) ? 'alert' : '' }}">
                    <span class="dc-label">{{ $s['label'] }}</span>
                    <span class="dc-value">{{ $s['value'] }}</span>
                    <span class="dc-sub">{{ $s['sub'] }}</span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Quick actions --}}
    @php $hasQA = auth()->user()?->canany(['manage blog','manage projects','manage services','manage team','manage pages','manage settings']); @endphp
    @if($hasQA)
        <div class="dash-section">
            <h3 class="dash-h3">Quick actions</h3>
            <div class="dash-actions">
                @can('manage blog')<a href="{{ route('admin.posts.create') }}" class="qa">＋ New blog post</a>@endcan
                @can('manage projects')<a href="{{ route('admin.projects.create') }}" class="qa">＋ New project</a>@endcan
                @can('manage services')<a href="{{ route('admin.services.create') }}" class="qa">＋ New service</a>@endcan
                @can('manage team')<a href="{{ route('admin.team.create') }}" class="qa">＋ New team member</a>@endcan
                @can('manage pages')<a href="{{ route('admin.pages.index') }}" class="qa">✎ Edit pages</a>@endcan
                @can('manage settings')<a href="{{ route('admin.settings.edit') }}" class="qa">⚙ Settings</a>@endcan
            </div>
        </div>
    @endif

    <div class="dash-grid">
        {{-- Recent messages --}}
        @can('view messages')
            <div class="dash-panel">
                <div class="dp-head">
                    <h3 class="dash-h3">Recent messages</h3>
                    <a href="{{ route('admin.messages.index') }}" class="dp-all">View all →</a>
                </div>
                @forelse($recentMessages as $m)
                    <a href="{{ route('admin.messages.show', $m) }}" class="dp-row {{ $m->is_read ? '' : 'unread' }}">
                        <div class="dp-main">
                            <span class="dp-title">{{ $m->name ?: 'Anonymous' }}@if(!$m->is_read)<span class="dot-new"></span>@endif</span>
                            <span class="dp-meta">{{ \Illuminate\Support\Str::limit($m->message ?? '', 64) }}</span>
                        </div>
                        <span class="dp-time">{{ $m->created_at?->diffForHumans(null, true) }}</span>
                    </a>
                @empty
                    <p class="dp-empty">No messages yet. Your contact form submissions will appear here.</p>
                @endforelse
            </div>
        @endcan

        {{-- Recent posts --}}
        @can('view blog')
            <div class="dash-panel">
                <div class="dp-head">
                    <h3 class="dash-h3">Recent blog posts</h3>
                    <a href="{{ route('admin.posts.index') }}" class="dp-all">View all →</a>
                </div>
                @forelse($recentPosts as $p)
                    <a href="{{ route('admin.posts.edit', $p) }}" class="dp-row">
                        <div class="dp-main">
                            <span class="dp-title">{{ $p->title }}</span>
                            <span class="dp-meta">Updated {{ $p->updated_at?->diffForHumans() }}</span>
                        </div>
                        <span class="chip {{ $p->status === 'published' ? 'chip-live' : '' }}">{{ $p->status }}</span>
                    </a>
                @empty
                    <p class="dp-empty">No posts yet. <a href="{{ route('admin.posts.create') }}">Write your first one →</a></p>
                @endforelse
            </div>
        @endcan
    </div>

    <style>
    .dash-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:24px;}
    .dash-hi{font-size:clamp(20px,3vw,26px);font-weight:600;font-family:'Space Grotesk',inherit;}
    .dash-date{color:var(--mut);font-size:14px;margin-top:4px;}

    .dash-maint{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;
        background:color-mix(in srgb,var(--acc) 12%,var(--panel));border:1px solid color-mix(in srgb,var(--acc) 45%,transparent);
        border-radius:12px;padding:14px 18px;margin-bottom:24px;font-size:14px;}

    .dash-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:30px;}
    .dash-card{display:flex;flex-direction:column;gap:2px;background:var(--panel);border:1px solid var(--bd);border-radius:14px;
        padding:18px;transition:transform .15s,border-color .2s;position:relative;overflow:hidden;}
    .dash-card::before{content:"";position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--acc-grad,var(--acc));opacity:0;transition:opacity .2s;}
    .dash-card:hover{transform:translateY(-2px);border-color:color-mix(in srgb,var(--acc) 45%,var(--bd));}
    .dash-card:hover::before{opacity:1;}
    .dash-card.alert{border-color:color-mix(in srgb,var(--acc) 55%,transparent);}
    .dash-card.alert::before{opacity:1;}
    .dc-label{font-size:12.5px;color:var(--mut);text-transform:uppercase;letter-spacing:.05em;}
    .dc-value{font-size:30px;font-weight:700;font-family:'Space Grotesk',inherit;line-height:1.1;margin-top:4px;}
    .dc-sub{font-size:12.5px;color:var(--mut);}

    .dash-section{margin-bottom:30px;}
    .dash-h3{font-size:15px;font-weight:600;margin-bottom:12px;}
    .dash-actions{display:flex;flex-wrap:wrap;gap:10px;}
    .qa{display:inline-flex;align-items:center;gap:6px;background:var(--panel);border:1px solid var(--bd);border-radius:10px;
        padding:10px 15px;font-size:13.5px;color:var(--tx);transition:border-color .2s,background .2s;}
    .qa:hover{border-color:color-mix(in srgb,var(--acc) 50%,var(--bd));background:var(--panel-2,var(--panel));}

    .dash-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
    @media (max-width:820px){.dash-grid{grid-template-columns:1fr;}}
    .dash-panel{background:var(--panel);border:1px solid var(--bd);border-radius:14px;padding:6px 4px 8px;}
    .dp-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px 10px;}
    .dp-all{font-size:13px;color:var(--mut);}
    .dp-all:hover{color:var(--acc);}
    .dp-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:11px 16px;border-top:1px solid var(--bd);transition:background .15s;}
    .dp-row:hover{background:var(--panel-2,rgba(255,255,255,.02));}
    .dp-main{min-width:0;}
    .dp-title{display:flex;align-items:center;gap:8px;font-size:14px;font-weight:500;}
    .dp-row.unread .dp-title{font-weight:600;}
    .dot-new{width:7px;height:7px;border-radius:50%;background:var(--acc);flex:none;}
    .dp-meta{display:block;font-size:12.5px;color:var(--mut);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:42ch;}
    .dp-time{font-size:12px;color:var(--mut);flex:none;white-space:nowrap;}
    .dp-empty{padding:16px;color:var(--mut);font-size:13.5px;}
    .chip-live{background:color-mix(in srgb,var(--acc) 22%,transparent);color:var(--acc);}
    </style>
@endsection
