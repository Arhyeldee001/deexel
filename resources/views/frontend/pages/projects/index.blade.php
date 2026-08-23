@extends('frontend.layouts.app')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <span class="eyebrow">Our work</span>
            <h1 class="h-sec" style="margin-top:10px;">Projects</h1>
            <p class="lead">A selection of products we've designed, built, and shipped.</p>
        </div>

        <div class="grid g-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="proj reveal">
                    <div class="thumb">
                        @if($project->featured_image)
                            <img src="{{ asset('storage/'.$project->featured_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            @include('frontend.partials.icon', ['name' => 'layers'])
                        @endif
                    </div>
                    <div class="meta">
                        <span class="cat">{{ $project->category }}</span>
                        <h3>{{ $project->title }}</h3>
                    </div>
                </a>
            @empty
                <p class="mut">Projects are being added soon.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
