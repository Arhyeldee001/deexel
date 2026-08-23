@extends('admin.layouts.app')

@section('title', 'Services')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Drag to reorder, toggle live on/off, or edit a service.</p>
        <a href="{{ route('admin.services.create') }}" class="btn-acc">+ New service</a>
    </div>

    <div class="crud-list" data-sortable data-reorder-url="{{ route('admin.services.reorder') }}">
        @forelse($services as $service)
            <div class="sec-item {{ $service->is_active ? '' : 'off' }}" data-id="{{ $service->id }}">
                <div class="sec-head">
                    <span class="drag" title="Drag to reorder">⠿</span>
                    <span class="thumb-ico">@include('frontend.partials.icon', ['name' => $service->icon ?: 'code'])</span>
                    <span class="sec-name">{{ $service->title }}</span>
                    <span class="badge">/{{ $service->slug }}</span>
                    <span class="spacer"></span>

                    <label class="toggle" title="Live on/off" onclick="event.stopPropagation()">
                        <input type="checkbox" data-toggle-url="{{ route('admin.services.toggle', $service) }}" {{ $service->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <a href="{{ route('admin.services.edit', $service) }}" class="icon-btn" title="Edit">✎</a>

                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No services yet. <a href="{{ route('admin.services.create') }}">Create your first one →</a></div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
