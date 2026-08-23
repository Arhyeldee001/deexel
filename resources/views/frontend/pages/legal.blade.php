@extends('frontend.layouts.app')

@section('content')
@include('frontend.partials.prose-style')
<section class="section">
    <div class="wrap" style="max-width:800px;">
        <h1 class="reveal" style="font-family:'Space Grotesk';font-size:clamp(28px,5vw,42px);font-weight:700;">{{ $page->title }}</h1>
        <p class="mut reveal" style="margin-top:10px;font-size:14px;">Last updated {{ $page->updated_at->format('F j, Y') }}</p>

        <div class="prose reveal">{!! $page->content !!}</div>
    </div>
</section>
@endsection
