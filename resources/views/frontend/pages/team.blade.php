@extends('frontend.layouts.app')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">Our people</span>
            <h1 class="h-sec" style="margin-top:10px;">Meet the team</h1>
            <p class="lead">The people behind Quin Tech.</p>
        </div>

        <div class="grid g-3">
            @forelse($members as $m)
                <div class="card glass reveal" style="text-align:center;">
                    <div class="team-avatar">
                        @if($m->photo)
                            <img src="{{ asset('storage/'.$m->photo) }}" alt="{{ $m->name }}">
                        @else
                            <span>{{ strtoupper(substr($m->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 style="margin-top:15px;">{{ $m->name }}</h3>
                    @if($m->role)<p style="color:var(--acc);font-size:13px;margin-top:2px;">{{ $m->role }}</p>@endif
                    @if($m->bio)<p class="mut" style="margin-top:10px;font-size:13.5px;">{{ $m->bio }}</p>@endif

                    @if(!empty($m->socials))
                        <div class="socials">
                            @if(!empty($m->socials['linkedin']))
                                <a href="{{ $m->socials['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 0 5 8.5a2.5 2.5 0 0 0-.02-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.4c0-1.3-.02-2.95-1.8-2.95-1.8 0-2.08 1.4-2.08 2.85V21H9z"/></svg>
                                </a>
                            @endif
                            @if(!empty($m->socials['x']))
                                <a href="{{ $m->socials['x'] }}" target="_blank" rel="noopener" aria-label="X">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-7.1 8.1L23 22h-6.6l-5.2-6.8L5.3 22H2l7.6-8.7L1.6 2h6.7l4.7 6.2zm-1.2 18h1.8L7.3 3.9H5.4z"/></svg>
                                </a>
                            @endif
                            @if(!empty($m->socials['github']))
                                <a href="{{ $m->socials['github'] }}" target="_blank" rel="noopener" aria-label="GitHub">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.53 2.36 1.09 2.94.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2z"/></svg>
                                </a>
                            @endif
                            @if(!empty($m->socials['website']))
                                <a href="{{ $m->socials['website'] }}" target="_blank" rel="noopener" aria-label="Website">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="mut">Team members coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

<style>
.team-avatar{width:88px;height:88px;border-radius:50%;margin:0 auto;overflow:hidden;background:color-mix(in srgb,var(--acc) 16%,transparent);display:flex;align-items:center;justify-content:center;border:1px solid var(--bd);}
.team-avatar img{width:100%;height:100%;object-fit:cover;}
.team-avatar span{font-family:'Space Grotesk';font-size:30px;font-weight:600;color:var(--acc);}
.socials{display:flex;gap:12px;justify-content:center;margin-top:14px;}
.socials a{width:34px;height:34px;border-radius:9px;border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;color:var(--mut);transition:color .2s,border-color .2s,transform .2s;}
.socials a:hover{color:var(--acc);border-color:color-mix(in srgb,var(--acc) 45%,transparent);transform:translateY(-2px);}
.socials svg{width:17px;height:17px;}
</style>
@endsection
