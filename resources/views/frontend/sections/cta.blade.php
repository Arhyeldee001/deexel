@php $c = $section->content ?? []; @endphp
<section class="section cta reveal">
    <div class="wrap">
        <div class="floaty">
            <h2>{{ $c['heading'] ?? "Have a project in mind? Let's build it." }}</h2>
            @if(!empty($c['button_label']))
                <a href="{{ $c['button_url'] ?? route('contact.index') }}" class="btn btn-acc">
                    {{ $c['button_label'] }} @include('frontend.partials.icon', ['name' => 'arrow'])
                </a>
            @endif
        </div>
    </div>
</section>
