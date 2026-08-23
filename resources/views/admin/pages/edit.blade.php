@extends('admin.layouts.app')

@section('title', 'Edit: '.$page->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <div class="editor-top">
        <a href="{{ route('admin.pages.index') }}" class="back-link">← All pages</a>
        <a href="{{ $page->slug === 'home' ? route('home') : url($page->slug) }}" target="_blank" class="btn-sm ghost">View page ↗</a>
    </div>

    {{-- Page settings (collapsible) --}}
    <div class="panel">
        <button type="button" class="panel-head" data-accordion>
            <span>Page settings</span>
            <span class="chev">▾</span>
        </button>
        <div class="panel-body">
            <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="form-grid">
                @csrf @method('PUT')
                <div class="fld">
                    <label>Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" required>
                </div>
                <div class="fld">
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" {{ $page->slug === 'home' ? 'readonly' : '' }} required>
                </div>
                <label class="checkline full">
                    <input type="checkbox" name="show_in_nav" value="1" {{ $page->show_in_nav ? 'checked' : '' }}> Show in navigation menu
                </label>
                <p class="mut full" style="font-size:13px;">SEO for this page (title, description, keywords, share image) lives in <strong>Settings → SEO</strong>.</p>
                <div class="full"><button type="submit" class="btn-acc">Save page settings</button></div>
            </form>
        </div>
    </div>

    {{-- Add a section --}}
    <form method="POST" action="{{ route('admin.sections.store', $page) }}" class="add-bar">
        @csrf
        <select name="type" required>
            <option value="" disabled selected>Add a section…</option>
            @foreach($types as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-acc">+ Add section</button>
    </form>

    {{-- Section list (drag to reorder) --}}
    <div id="sections" data-reorder-url="{{ route('admin.sections.reorder') }}">
        @forelse($page->sections as $section)
            <div class="sec-item {{ $section->is_active ? '' : 'off' }}" data-id="{{ $section->id }}">
                <div class="sec-head">
                    <span class="drag" title="Drag to reorder">⠿</span>
                    <span class="sec-name">{{ $section->name ?: ucfirst($section->type) }}</span>
                    <span class="badge">{{ str_replace('_', ' ', $section->type) }}</span>
                    <span class="spacer"></span>

                    <label class="toggle" title="Section on/off" onclick="event.stopPropagation()">
                        <input type="checkbox" data-toggle-url="{{ route('admin.sections.toggle', $section) }}" {{ $section->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <button type="button" class="icon-btn" data-expand title="Edit">✎</button>

                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" data-confirm="Remove this section?" onclick="event.stopPropagation()">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>

                <div class="sec-body">
                    <form method="POST" action="{{ route('admin.sections.update', $section) }}" enctype="multipart/form-data" class="sec-form">
                        @csrf @method('PUT')
                        @php $view = 'admin.sections.fields.'.str_replace('_', '-', $section->type); @endphp
                        @if(view()->exists($view))
                            @include($view, ['section' => $section])
                        @else
                            <p class="muted">This section type has no editable fields yet.</p>
                        @endif
                        <div class="sec-actions">
                            <button type="submit" class="btn-acc">Save section</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No sections yet. Add one above to start building this page.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-sections.js') }}"></script>
@endpush
