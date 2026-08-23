@extends('frontend.layouts.app')

@section('content')
@include('frontend.partials.prose-style')
<article class="section">
    <div class="wrap" style="max-width:800px;">
        <a href="{{ route('blog.index') }}" class="mut" style="font-size:14px;">← All articles</a>

        @if($post->category)
            <span class="reveal" style="display:block;color:var(--acc);font-size:13px;text-transform:uppercase;letter-spacing:.05em;margin-top:20px;">{{ $post->category->name }}</span>
        @endif
        <h1 class="reveal" style="font-family:'Space Grotesk';font-size:clamp(28px,5vw,46px);font-weight:700;margin-top:8px;line-height:1.1;">{{ $post->title }}</h1>

        <div class="reveal mut" style="font-size:13.5px;margin-top:14px;">
            {{ optional($post->published_at)->format('F j, Y') }}
            @if($post->author) · by {{ $post->author->name }} @endif
        </div>

        @if($post->featured_image)
            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="border-radius:18px;margin-top:24px;width:100%;" class="reveal">
        @endif

        @if($post->excerpt)
            <p class="lead reveal" style="margin-top:22px;">{{ $post->excerpt }}</p>
        @endif

        @if($post->body)
            <div class="prose reveal">{!! $post->body !!}</div>
        @endif
    </div>

    @if($related->isNotEmpty())
        <div class="wrap" style="margin-top:60px;">
            <h2 class="h-sec reveal" style="font-size:24px;text-align:center;margin-bottom:24px;">More articles</h2>
            <div class="grid g-3">
                @foreach($related as $r)
                    <a href="{{ route('blog.show', $r) }}" class="post-card glass reveal">
                        <div class="post-thumb">
                            @if($r->featured_image)
                                <img src="{{ asset('storage/'.$r->featured_image) }}" alt="{{ $r->title }}">
                            @else
                                <span class="post-thumb-ico">@include('frontend.partials.icon', ['name' => 'chat'])</span>
                            @endif
                        </div>
                        <div class="post-body">
                            @if($r->category)<span class="post-cat">{{ $r->category->name }}</span>@endif
                            <h3>{{ $r->title }}</h3>
                            <span class="post-meta">{{ optional($r->published_at)->format('d M Y') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</article>

<style>
.post-card{border-radius:16px;overflow:hidden;display:flex;flex-direction:column;transition:transform .28s,border-color .28s;}
.post-card:hover{transform:translateY(-5px);border-color:rgba(255,255,255,.26);}
.post-thumb{aspect-ratio:16/10;background:linear-gradient(135deg,#1c1c2a,#141420);display:flex;align-items:center;justify-content:center;}
.post-thumb img{width:100%;height:100%;object-fit:cover;}
.post-thumb-ico{color:var(--acc);}
.post-thumb-ico svg{width:34px;height:34px;}
.post-body{padding:18px;}
.post-cat{font-size:11px;color:var(--acc);text-transform:uppercase;letter-spacing:.05em;}
.post-body h3{font-size:16px;margin:6px 0 8px;}
.post-meta{display:block;margin-top:10px;font-size:12px;color:var(--mut);}
</style>
@endsection
