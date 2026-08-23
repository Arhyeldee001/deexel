@php
    $c = $section->content ?? [];
    $checks = $c['checks'] ?? [];
    $rows = $c['rows'] ?? [];
@endphp
<section class="section">
    <div class="wrap">
        <div class="sec-head center reveal">
            <h2 class="h-sec">{{ $c['heading'] ?? 'Powerful capabilities' }}</h2>
            @if(!empty($c['subheading']))<p class="lead">{{ $c['subheading'] }}</p>@endif
        </div>

        <div class="features">
            <div class="reveal">
                <h3 style="font-size:clamp(20px,4vw,26px)">{{ $c['left_heading'] ?? 'Built to help you see the bigger picture' }}</h3>
                @if(!empty($c['left_body']))<p class="mut" style="margin-top:12px;font-size:14.5px">{{ $c['left_body'] }}</p>@endif
                @if(!empty($c['left_cta_label']))
                    <a href="{{ $c['left_cta_url'] ?? '#' }}" class="btn glass" style="margin-top:16px">
                        {{ $c['left_cta_label'] }} @include('frontend.partials.icon', ['name' => 'arrow'])
                    </a>
                @endif
                @if(!empty($checks))
                    <ul class="checks">
                        @foreach($checks as $check)
                            <li><span class="chk">@include('frontend.partials.icon', ['name' => 'check'])</span>{{ $check }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="feat-visual glass reveal">
                <div class="ring"></div>
                <div style="position:absolute;right:24px;bottom:28px;color:#fff;opacity:.9">
                    <span style="display:block;width:58px;height:58px">@include('frontend.partials.icon', ['name' => 'shield'])</span>
                </div>
                @foreach($rows as $i => $row)
                    <div class="feat-row glass {{ $i > 1 ? 'dim' : '' }}">
                        <span class="ico">@include('frontend.partials.icon', ['name' => $row['icon'] ?? 'layers'])</span>
                        <div>
                            <div class="t">{{ $row['title'] ?? '' }}</div>
                            <div class="d">{{ $row['text'] ?? '' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
