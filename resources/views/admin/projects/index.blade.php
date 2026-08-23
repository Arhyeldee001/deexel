@extends('admin.layouts.app')

@section('title', 'Projects')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Drag to reorder, toggle live on/off, or edit a project.</p>
        <a href="{{ route('admin.projects.create') }}" class="btn-acc">+ New project</a>
    </div>

    <div class="crud-list" data-sortable data-reorder-url="{{ route('admin.projects.reorder') }}">
        @forelse($projects as $project)
            <div class="sec-item {{ $project->is_active ? '' : 'off' }}" data-id="{{ $project->id }}">
                <div class="sec-head">
                    <span class="drag" title="Drag to reorder">⠿</span>
                    @if($project->featured_image)
                        <span class="thumb-img"><img src="{{ asset('storage/'.$project->featured_image) }}" alt=""></span>
                    @else
                        <span class="thumb-ico">@include('frontend.partials.icon', ['name' => 'layers'])</span>
                    @endif
                    <span class="sec-name">{{ $project->title }}</span>
                    @if($project->category)<span class="badge">{{ $project->category }}</span>@endif
                    <span class="spacer"></span>

                    <label class="toggle" title="Live on/off" onclick="event.stopPropagation()">
                        <input type="checkbox" data-toggle-url="{{ route('admin.projects.toggle', $project) }}" {{ $project->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <a href="{{ route('admin.projects.edit', $project) }}" class="icon-btn" title="Edit">✎</a>

                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No projects yet. <a href="{{ route('admin.projects.create') }}">Create your first one →</a></div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
