@extends('admin.layouts.app')

@section('title', 'Pages & sections')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <p class="muted-lead">Toggle a page on/off, control whether it shows in the nav, or open it to edit its sections.</p>

    <div class="page-list">
        @foreach($pages as $page)
            <div class="page-row">
                <div class="page-main">
                    <div class="page-title">{{ $page->title }}
                        @if($page->is_system)<span class="chip">core</span>@endif
                    </div>
                    <div class="page-meta">/{{ $page->slug }} · {{ $page->sections_count }} section{{ $page->sections_count === 1 ? '' : 's' }}</div>
                </div>

                <div class="page-controls">
                    <label class="toggle" title="Show in navigation">
                        <input type="checkbox" data-toggle-url="{{ route('admin.pages.toggleNav', $page) }}" {{ $page->show_in_nav ? 'checked' : '' }}>
                        <span class="track"></span>
                        <span class="toggle-label">In nav</span>
                    </label>

                    <label class="toggle" title="Page live on/off">
                        <input type="checkbox" data-toggle-url="{{ route('admin.pages.toggle', $page) }}" {{ $page->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                        <span class="toggle-label">Live</span>
                    </label>

                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn-sm">Edit sections →</a>
                </div>
            </div>
        @endforeach
    </div>

    @can('view legal')
        <div class="legal-block">
            <div class="legal-head">
                <div>
                    <h2>Legal pages</h2>
                    <p class="muted-lead" style="margin:0;">Policies shown in the site footer when active.</p>
                </div>
                @can('manage legal')<a href="{{ route('admin.legal.create') }}" class="btn-sm">+ New legal page</a>@endcan
            </div>

            <div class="page-list">
                @foreach($legalPages as $lp)
                    <div class="page-row {{ $lp->is_active ? '' : 'dimmed' }}">
                        <div class="page-main">
                            <div class="page-title">{{ $lp->title }}</div>
                            <div class="page-meta">/legal/{{ $lp->slug }} · updated {{ $lp->updated_at->format('d M Y') }}</div>
                        </div>
                        <div class="page-controls">
                            @can('manage legal')
                                <label class="toggle" title="Active on/off">
                                    <input type="checkbox" data-toggle-url="{{ route('admin.legal.toggle', $lp) }}" {{ $lp->is_active ? 'checked' : '' }}>
                                    <span class="track"></span>
                                    <span class="toggle-label">Active</span>
                                </label>
                                <a href="{{ route('admin.legal.edit', $lp) }}" class="btn-sm">Edit →</a>
                                <form method="POST" action="{{ route('admin.legal.destroy', $lp) }}" data-confirm="Delete this legal page?" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                                </form>
                            @else
                                <span class="chip">{{ $lp->is_active ? 'active' : 'hidden' }}</span>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endcan

    <style>
    .legal-block{margin-top:40px;padding-top:26px;border-top:1px solid var(--bd);}
    .legal-head{display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:16px;}
    .legal-head h2{font-size:18px;margin-bottom:2px;}
    .page-row.dimmed{opacity:.6;}
    .page-controls form{display:inline;}
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-sections.js') }}"></script>
@endpush
