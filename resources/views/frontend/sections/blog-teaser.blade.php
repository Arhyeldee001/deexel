@php
    use App\Models\Post;
    $c = $section->content ?? [];
    $posts = Post::published()->latest('published_at')->take($c['limit'] ?? 3)->get();
    $bgs = [
        'linear-gradient(135deg,#e8e6df,#cfccc2)',
        'linear-gradient(135deg,#d9d6cf,#efc0d6 60%,#8fd3c0)',
        'linear-gradient(135deg,#eef0e6,#bfe6d8 55%,#dfe89a)',
    ];
@endphp
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <h2 class="h-sec">{{ $c['heading'] ?? 'Insights & inspiration' }}</h2>
            @if(!empty($c['subheading']))<p class="lead">{{ $c['subheading'] }}</p>@endif
        </div>

        <div class="grid g-3">
            @forelse($posts as $i => $post)
                <a href="{{ route('blog.show', $post) }}" class="blog reveal">
                    <div class="bg" style="background:{{ $bgs[$i % count($bgs)] }}">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover">
                        @endif
                    </div>
                    <span class="arrow">@include('frontend.partials.icon', ['name' => 'arrow-ur'])</span>
                    @if($i === 0)
                        <div class="cap glass">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->excerpt }}</p>
                        </div>
                    @endif
                </a>
            @empty
                <p class="mut">No posts yet — publish articles in the admin panel.</p>
            @endforelse
        </div>
    </div>
</section>
