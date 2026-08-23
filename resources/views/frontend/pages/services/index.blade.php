@extends('frontend.layouts.app')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">What we do</span>
            <h1 class="h-sec" style="margin-top:10px;">Our services</h1>
            <p class="lead">End-to-end product work — from first idea to a shipped, maintained product.</p>
        </div>

        <div class="grid g-3">
            @forelse($services as $service)
                <a href="{{ route('services.show', $service) }}" class="card glass reveal">
                    <div class="ico">@include('frontend.partials.icon', ['name' => $service->icon ?: 'code'])</div>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->excerpt }}</p>
                </a>
            @empty
                <p class="mut">Services are being added soon.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
