@extends('admin.layouts.app')

@section('title', 'Legal & maintenance')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    @can('manage settings')
        <div class="maint-card {{ $mMode ? 'on' : '' }}">
            <form method="POST" action="{{ route('admin.maintenance.update') }}">
                @csrf
                <div class="maint-head">
                    <div>
                        <h3>Maintenance mode</h3>
                        <p class="mut">When on, visitors see a maintenance page. You (and other admins) can still browse the site.</p>
                    </div>
                    <label class="toggle big" title="Maintenance on/off">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ $mMode ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>
                </div>
                <div class="form-grid" style="margin-top:14px;">
                    <div class="fld full">
                        <label>Heading</label>
                        <input type="text" name="maintenance_heading" value="{{ $mHead }}">
                    </div>
                    <div class="fld full">
                        <label>Message</label>
                        <textarea name="maintenance_message" rows="2">{{ $mMsg }}</textarea>
                    </div>
                    <div class="full"><button type="submit" class="btn-acc">Save maintenance settings</button></div>
                </div>
            </form>
        </div>
    @endcan

    <div class="crud-top" style="margin-top:26px;">
        <p class="muted-lead">Edit your legal policies. Only active pages appear in the site footer.</p>
        @can('manage legal')<a href="{{ route('admin.legal.create') }}" class="btn-acc">+ New legal page</a>@endcan
    </div>

    <div class="crud-list" style="max-width:720px;">
        @forelse($pages as $page)
            <div class="sec-item {{ $page->is_active ? '' : 'off' }}" data-id="{{ $page->id }}">
                <div class="sec-head">
                    <span class="sec-name">{{ $page->title }}</span>
                    <span class="badge">/legal/{{ $page->slug }}</span>
                    <span class="spacer"></span>
                    <span class="post-date">Updated {{ $page->updated_at->format('d M Y') }}</span>

                    @can('manage legal')
                        <label class="toggle" title="Active on/off">
                            <input type="checkbox" data-toggle-url="{{ route('admin.legal.toggle', $page) }}" {{ $page->is_active ? 'checked' : '' }}>
                            <span class="track"></span>
                        </label>
                        <a href="{{ route('admin.legal.edit', $page) }}" class="icon-btn" title="Edit">✎</a>
                        <form method="POST" action="{{ route('admin.legal.destroy', $page) }}" data-confirm="Delete this legal page?">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="empty">No legal pages.</div>
        @endforelse
    </div>

    <style>
    .maint-card{background:var(--panel);border:1px solid var(--bd);border-radius:14px;padding:22px;max-width:720px;transition:border-color .2s;}
    .maint-card.on{border-color:color-mix(in srgb,var(--acc) 55%,transparent);box-shadow:0 0 0 1px color-mix(in srgb,var(--acc) 25%,transparent);}
    .maint-head{display:flex;align-items:center;justify-content:space-between;gap:16px;}
    .maint-head h3{font-size:17px;}
    .toggle.big{transform:scale(1.15);}
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
