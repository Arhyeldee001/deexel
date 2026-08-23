@extends('admin.layouts.app')

@section('title', 'FAQs')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Drag to reorder, toggle on/off, or edit a question. Add categories to auto-group them on the site.</p>
        <a href="{{ route('admin.faqs.create') }}" class="btn-acc">+ New FAQ</a>
    </div>

    <div class="crud-list" data-sortable data-reorder-url="{{ route('admin.faqs.reorder') }}">
        @forelse($faqs as $faq)
            <div class="sec-item {{ $faq->is_active ? '' : 'off' }}" data-id="{{ $faq->id }}">
                <div class="sec-head">
                    <span class="drag" title="Drag to reorder">⠿</span>
                    <span class="sec-name">{{ $faq->question }}</span>
                    @if($faq->category)<span class="badge">{{ $faq->category }}</span>@endif
                    <span class="spacer"></span>

                    <label class="toggle" title="On/off" onclick="event.stopPropagation()">
                        <input type="checkbox" data-toggle-url="{{ route('admin.faqs.toggle', $faq) }}" {{ $faq->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="icon-btn" title="Edit">✎</a>

                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Delete this FAQ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No FAQs yet. <a href="{{ route('admin.faqs.create') }}">Add the first one →</a></div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
