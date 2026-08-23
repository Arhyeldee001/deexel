@extends('admin.layouts.app')

@section('title', $user->exists ? 'Edit user' : 'New user')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.users.index') }}" class="back-link">← All users</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
          class="crud-form">
        @csrf
        @if($user->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="fld">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="fld full">
                <label>Password
                    <span class="hint">{{ $user->exists ? '(leave blank to keep current)' : '(min 8 characters)' }}</span>
                </label>
                <input type="password" name="password" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
            </div>

            <div class="fld full">
                <label class="sub-label">Roles</label>
                @php $userRoles = old('roles', $user->exists ? $user->roles->pluck('name')->all() : []); @endphp
                <div class="perm-grid">
                    @foreach($roles as $role)
                        <label class="perm-item">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $user->exists ? 'Save changes' : 'Create user' }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>

    <style>
    .perm-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;}
    .perm-item{display:flex;align-items:center;gap:9px;background:var(--panel-2);border:1px solid var(--bd);border-radius:9px;padding:11px 13px;font-size:14px;cursor:pointer;text-transform:capitalize;transition:border-color .2s;}
    .perm-item:hover{border-color:rgba(255,255,255,.25);}
    </style>
@endsection
