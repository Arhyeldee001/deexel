@extends('frontend.layouts.app')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">Journal</span>
            <h1 class="h-sec" style="margin-top:10px;">Insights &amp; inspiration</h1>
            <p class="lead">Articles, case studies, and lessons from building software in Nigeria.</p>
        </div>

        @if($categories->isNotEmpty())
            <div class="cat-filter reveal">
                <a href="{{ route('blog.index') }}" class="chip-link {{ ! $activeCat ? 'on' : '' }}">All</a>
                @foreach($categories as $c)
                    <a href="{{ route('blog.index', ['category' => $c->slug]) }}" class="chip-link {{ $activeCat === $c->slug ? 'on' : '' }}">{{ $c->name }}</a>
                @endforeach
            </div>
        @endif

        <div class="grid g-3" style="margin-top:26px;">
            @forelse($posts as $post)
                <a href="{{ route('blog.show', $post) }}" class="post-card glass reveal">
                    <div class="post-thumb">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}">
                        @else
                            <span class="post-thumb-ico">@include('frontend.partials.icon', ['name' => 'chat'])</span>
                        @endif
                    </div>
                    <div class="post-body">
                        @if($post->category)<span class="post-cat">{{ $post->category->name }}</span>@endif
                        <h3>{{ $post->title }}</h3>
                        <p class="mut">{{ $post->excerpt }}</p>
                        <span class="post-meta">{{ optional($post->published_at)->format('d M Y') }}</span>
                    </div>
                </a>
            @empty
                <p class="mut">No articles yet — check back soon.</p>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="pager reveal">
                @if($posts->onFirstPage())
                    <span class="btn-sm ghost disabled">← Prev</span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}" class="btn-sm">← Prev</a>
                @endif
                <span class="mut">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</span>
                @if($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}" class="btn-sm">Next →</a>
                @else
                    <span class="btn-sm ghost disabled">Next →</span>
                @endif
            </div>
        @endif
    </div>
</section>

<style>
.cat-filter{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:22px;}
.chip-link{font-size:13px;color:var(--mut);border:1px solid var(--bd);border-radius:999px;padding:7px 15px;transition:color .2s,border-color .2s;}
.chip-link:hover,.chip-link.on{color:var(--tx);border-color:color-mix(in srgb,var(--acc) 50%,transparent);}
.post-card{border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:transform .28s,border-color .28s;}
.post-card:hover{transform:translateY(-5px);border-color:rgba(255,255,255,.26);}
.post-thumb{aspect-ratio:16/10;background:linear-gradient(135deg,#1c1c2a,#141420);display:flex;align-items:center;justify-content:center;}
.post-thumb img{width:100%;height:100%;object-fit:cover;}
.post-thumb-ico{color:var(--acc);}
.post-thumb-ico svg{width:34px;height:34px;}
.post-body{padding:18px;}
.post-cat{font-size:11px;color:var(--acc);text-transform:uppercase;letter-spacing:.05em;}
.post-body h3{font-size:17px;margin:6px 0 8px;}
.post-body p{font-size:13.5px;}
.post-meta{display:block;margin-top:12px;font-size:12px;color:var(--mut);}
.pager{display:flex;align-items:center;justify-content:center;gap:18px;margin-top:36px;}
.btn-sm.disabled{opacity:.4;pointer-events:none;}
</style>
@endsection
