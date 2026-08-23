@php
    $c = $section->content ?? [];
    $items = $c['items'] ?? [];
@endphp
<section class="section" style="padding-top:0">
    <div class="wrap">
        <div class="stats glass reveal">
            @forelse($items as $item)
                <div>
                    <div class="n">{{ $item['value'] ?? '' }}</div>
                    <div class="l">{{ $item['label'] ?? '' }}</div>
                </div>
            @empty
                <div><div class="n">—</div><div class="l">Add stats in the admin panel</div></div>
            @endforelse
        </div>
    </div>
</section>
