@extends('frontend.layouts.app')

@section('content')
<section class="section" style="min-height:56vh;display:flex;align-items:center;justify-content:center;text-align:center;">
    <div class="wrap">
        <span class="eyebrow">{{ $pageName }}</span>
        <h1 class="h-sec" style="margin-top:12px;">Coming soon</h1>
        <p class="lead" style="margin:14px auto 26px;">This page is being built. Check back shortly.</p>
        <a href="{{ route('home') }}" class="btn btn-acc">Back home →</a>
    </div>
</section>
@endsection
