@extends('frontend.layouts.app')

@section('content')
@include('frontend.partials.prose-style')
<section class="section">
    <div class="wrap" style="max-width:880px;">
        <a href="{{ route('projects.index') }}" class="mut" style="font-size:14px;">← All projects</a>

        @if($project->category)<span class="cat reveal" style="display:block;color:var(--acc);font-size:13px;margin-top:20px;">{{ $project->category }}</span>@endif
        <h1 class="reveal" style="font-family:'Space Grotesk';font-size:clamp(28px,5vw,46px);font-weight:700;margin-top:6px;">{{ $project->title }}</h1>
        @if($project->excerpt)
            <p class="lead reveal" style="margin-top:14px;">{{ $project->excerpt }}</p>
        @endif

        @if($project->featured_image)
            <img src="{{ asset('storage/'.$project->featured_image) }}" alt="{{ $project->title }}" style="border-radius:18px;margin-top:26px;width:100%;" class="reveal">
        @endif

        <div class="proj-meta reveal" style="display:flex;flex-wrap:wrap;gap:24px;margin-top:26px;font-size:14px;">
            @if($project->client)<div><span class="mut">Client</span><br>{{ $project->client }}</div>@endif
            @if($project->completed_at)<div><span class="mut">Completed</span><br>{{ $project->completed_at->format('M Y') }}</div>@endif
            @if($project->project_url)<div><span class="mut">Link</span><br><a href="{{ $project->project_url }}" target="_blank" rel="noopener" style="color:var(--acc);">Visit site ↗</a></div>@endif
        </div>

        @if($project->body)
            <div class="prose reveal">{!! $project->body !!}</div>
        @endif

        <div style="margin-top:34px;" class="reveal">
            <a href="{{ route('contact.index') }}" class="btn btn-acc">Start a project like this →</a>
        </div>
    </div>
</section>
@endsection
