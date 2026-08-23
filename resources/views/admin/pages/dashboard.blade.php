@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="stat-grid">
        @foreach($stats as $label => $count)
            <div class="stat">
                <div class="n">{{ $count }}</div>
                <div class="l">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="hint">
        Welcome to the Quin Tech admin. This is the shell — the modules (Pages &amp; the section editor,
        Services, Projects, Team, Blog, FAQs, Messages, Settings, SEO, and Users &amp; Roles) will appear
        in the sidebar as we build them next.
    </div>
@endsection
