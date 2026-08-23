@extends('admin.layouts.app')

@section('title', 'Users')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Create admins and sub-admins, and assign each one a role.</p>
        <a href="{{ route('admin.users.create') }}" class="btn-acc">+ New user</a>
    </div>

    <div class="crud-list">
        @foreach($users as $user)
            <div class="sec-item">
                <div class="sec-head">
                    <span class="avatar avatar-blank">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    <span class="sec-name">{{ $user->name }}</span>
                    <span class="badge">{{ $user->email }}</span>
                    <span class="spacer"></span>
                    @foreach($user->roles as $role)
                        <span class="role-chip {{ $role->name === 'super-admin' ? 'super' : '' }}">{{ $role->name }}</span>
                    @endforeach

                    <a href="{{ route('admin.users.edit', $user) }}" class="icon-btn" title="Edit">✎</a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <style>
    .role-chip{font-size:11px;border-radius:6px;padding:3px 9px;border:1px solid var(--bd);color:var(--mut);text-transform:capitalize;}
    .role-chip.super{color:var(--acc);border-color:color-mix(in srgb,var(--acc) 40%,transparent);background:color-mix(in srgb,var(--acc) 12%,transparent);}
    </style>
@endsection
