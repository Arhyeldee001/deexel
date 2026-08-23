@extends('frontend.layouts.app')

@section('content')
@include('frontend.partials.prose-style')
<section class="section">
    <div class="wrap" style="max-width:820px;">
        <a href="{{ route('services.index') }}" class="mut" style="font-size:14px;">← All services</a>

        <div class="ico reveal" style="width:56px;height:56px;border-radius:14px;background:color-mix(in srgb,var(--acc) 16%,transparent);color:var(--acc);display:flex;align-items:center;justify-content:center;margin-top:22px;">
            @include('frontend.partials.icon', ['name' => $service->icon ?: 'code'])
        </div>

        <h1 class="reveal" style="font-family:'Space Grotesk';font-size:clamp(28px,5vw,44px);font-weight:700;margin-top:18px;">{{ $service->title }}</h1>
        @if($service->excerpt)
            <p class="lead reveal" style="margin-top:14px;">{{ $service->excerpt }}</p>
        @endif

        @if($service->image)
            <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->title }}" style="border-radius:16px;margin-top:26px;width:100%;" class="reveal">
        @endif

        @if($service->body)
            <div class="prose reveal">{!! $service->body !!}</div>
        @endif

        <div style="margin-top:34px;" class="reveal">
            <a href="{{ route('contact.index') }}" class="btn btn-acc">Start a project →</a>
        </div>
    </div>
</section>
@endsection
