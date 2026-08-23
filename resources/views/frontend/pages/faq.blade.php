@extends('frontend.layouts.app')

@php
    $grouped = $faqs->groupBy(fn ($f) => $f->category ?: 'General');
    $multi = $grouped->count() > 1;
@endphp

@section('content')
<section class="section">
    <div class="wrap" style="max-width:820px;">
        <div class="sec-head center reveal">
            <span class="eyebrow">Questions</span>
            <h1 class="h-sec" style="margin-top:10px;">Frequently asked</h1>
            <p class="lead">Everything you might want to know before we start.</p>
        </div>

        @if($faqs->isEmpty())
            <p class="mut" style="text-align:center;">No questions yet — check back soon.</p>
        @endif

        @foreach($grouped as $category => $items)
            @if($multi)
                <h2 class="faq-group reveal">{{ $category }}</h2>
            @endif
            <div class="faq-list reveal">
                @foreach($items as $faq)
                    <div class="faq-item">
                        <button type="button" class="faq-q">
                            <span>{{ $faq->question }}</span>
                            <span class="faq-ic">+</span>
                        </button>
                        <div class="faq-a"><div class="faq-a-in">{!! nl2br(e($faq->answer)) !!}</div></div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="center" style="margin-top:36px;">
            <p class="mut" style="margin-bottom:14px;">Still have a question?</p>
            <a href="{{ route('contact.index') }}" class="btn btn-acc">Get in touch →</a>
        </div>
    </div>
</section>

<style>
.faq-group{font-family:'Space Grotesk';font-size:20px;margin:30px 0 12px;}
.faq-list{display:flex;flex-direction:column;gap:10px;}
.faq-item{background:var(--surf);border:1px solid var(--bd);border-radius:12px;overflow:hidden;-webkit-backdrop-filter:blur(16px);backdrop-filter:blur(16px);}
.faq-q{width:100%;display:flex;align-items:center;justify-content:space-between;gap:14px;background:none;border:0;color:var(--tx);font-family:inherit;font-size:15.5px;font-weight:500;text-align:left;padding:17px 18px;cursor:pointer;}
.faq-ic{font-size:22px;color:var(--acc);line-height:1;transition:transform .3s;flex:none;}
.faq-item.open .faq-ic{transform:rotate(45deg);}
.faq-a{max-height:0;opacity:0;overflow:hidden;transition:max-height .35s ease,opacity .3s;}
.faq-item.open .faq-a{max-height:600px;opacity:1;}
.faq-a-in{padding:0 18px 18px;color:rgba(245,245,248,.72);font-size:14.5px;line-height:1.7;}
</style>

<script>
document.querySelectorAll('.faq-q').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var item = btn.closest('.faq-item');
        var open = item.classList.contains('open');
        // Close siblings in the same list for a clean accordion feel
        item.closest('.faq-list').querySelectorAll('.faq-item.open').forEach(function (el) {
            if (el !== item) el.classList.remove('open');
        });
        item.classList.toggle('open', !open);
    });
});
</script>
@endsection
