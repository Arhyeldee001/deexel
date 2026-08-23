@php
    use App\Models\Project;
    $c = $section->content ?? [];
    $projects = Project::where('is_active', true)->orderBy('sort_order')->take($c['limit'] ?? 3)->get();
@endphp
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <h2 class="h-sec">{{ $c['heading'] ?? 'Featured projects' }}</h2>
            @if(!empty($c['subheading']))<p class="lead">{{ $c['subheading'] }}</p>@endif
        </div>

        <div class="grid g-3">
            @forelse($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="proj reveal">
                    <div class="thumb">
                        @if($project->featured_image)
                            <img src="{{ asset('storage/'.$project->featured_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover">
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
                <p class="mut">No projects yet — add them in the admin panel.</p>
            @endforelse
        </div>
    </div>
</section>
