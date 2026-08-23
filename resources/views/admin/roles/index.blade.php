@extends('admin.layouts.app')

@section('title', 'Roles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if(session('error'))<div class="flash-seed" data-msg="{{ session('error') }}" data-type="error"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Roles bundle permissions. Assign a role to a user to grant those permissions.</p>
        <a href="{{ route('admin.roles.create') }}" class="btn-acc">+ New role</a>
    </div>

    <div class="crud-list" style="max-width:720px;">
        @foreach($roles as $role)
            <div class="sec-item">
                <div class="sec-head">
                    <span class="sec-name" style="text-transform:capitalize;">{{ $role->name }}</span>
                    @if($role->name === 'super-admin')
                        <span class="badge">full access</span>
                    @else
                        <span class="badge">{{ $role->permissions_count }} permission{{ $role->permissions_count === 1 ? '' : 's' }}</span>
                    @endif
                    <span class="badge">{{ $role->users_count }} user{{ $role->users_count === 1 ? '' : 's' }}</span>
                    <span class="spacer"></span>

                    @if($role->name !== 'super-admin')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="icon-btn" title="Edit permissions">✎</a>
                        @if($role->users_count === 0)
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                            </form>
                        @endif
                    @else
                        <span class="mut" style="font-size:12px;">locked</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
