@extends('admin.layouts.app')

@section('title', 'Team')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Drag to reorder, toggle visible on/off, or edit a team member.</p>
        <a href="{{ route('admin.team.create') }}" class="btn-acc">+ New member</a>
    </div>

    <div class="crud-list" data-sortable data-reorder-url="{{ route('admin.team.reorder') }}">
        @forelse($members as $member)
            <div class="sec-item {{ $member->is_active ? '' : 'off' }}" data-id="{{ $member->id }}">
                <div class="sec-head">
                    <span class="drag" title="Drag to reorder">⠿</span>
                    @if($member->photo)
                        <span class="avatar"><img src="{{ asset('storage/'.$member->photo) }}" alt=""></span>
                    @else
                        <span class="avatar avatar-blank">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    @endif
                    <span class="sec-name">{{ $member->name }}</span>
                    @if($member->role)<span class="badge">{{ $member->role }}</span>@endif
                    <span class="spacer"></span>

                    <label class="toggle" title="Visible on/off" onclick="event.stopPropagation()">
                        <input type="checkbox" data-toggle-url="{{ route('admin.team.toggle', $member) }}" {{ $member->is_active ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <a href="{{ route('admin.team.edit', $member) }}" class="icon-btn" title="Edit">✎</a>

                    <form method="POST" action="{{ route('admin.team.destroy', $member) }}" onsubmit="return confirm('Remove this member?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No team members yet. <a href="{{ route('admin.team.create') }}">Add the first one →</a></div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
