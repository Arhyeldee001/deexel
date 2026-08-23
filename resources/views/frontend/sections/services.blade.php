@php
    use App\Models\Service;
    $c = $section->content ?? [];
    $services = Service::where('is_active', true)->orderBy('sort_order')->take($c['limit'] ?? 6)->get();
@endphp
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <h2 class="h-sec">{{ $c['heading'] ?? 'What we do' }}</h2>
            @if(!empty($c['subheading']))<p class="lead">{{ $c['subheading'] }}</p>@endif
        </div>

        <div class="grid g-3">
            @forelse($services as $service)
                <a href="{{ route('services.show', $service) }}" class="card glass reveal">
                    <div class="ico">@include('frontend.partials.icon', ['name' => $service->icon ?: 'code'])</div>
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->excerpt }}</p>
                </a>
            @empty
                <p class="mut">No services yet — add them in the admin panel.</p>
            @endforelse
        </div>
    </div>
</section>
