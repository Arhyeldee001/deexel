@extends('admin.layouts.app')

@section('title', $role->exists ? 'Edit role' : 'New role')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.roles.index') }}" class="back-link">← All roles</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
          class="crud-form">
        @csrf
        @if($role->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld full">
                <label>Role name</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required placeholder="e.g. content editor">
                <span class="hint">Spaces become dashes (e.g. “content editor” → content-editor).</span>
            </div>

            <div class="fld full">
                <label class="sub-label">Permissions per module</label>
                @php $sel = old('permissions', $assigned); @endphp
                <table class="perm-table">
                    <thead>
                        <tr><th>Module</th><th>Read</th><th>Write</th></tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $module => $perms)
                            <tr data-module-row>
                                <td class="pm-name">{{ $module }}</td>
                                <td>
                                    @if(!empty($perms['read']))
                                        <input type="checkbox" name="permissions[]" value="{{ $perms['read'] }}" data-read {{ in_array($perms['read'], $sel) ? 'checked' : '' }}>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($perms['write']))
                                        <input type="checkbox" name="permissions[]" value="{{ $perms['write'] }}" data-write {{ in_array($perms['write'], $sel) ? 'checked' : '' }}>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="hint" style="margin-top:8px;">Write access automatically includes Read.</p>
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $role->exists ? 'Save role' : 'Create role' }}</button>
                <a href="{{ route('admin.roles.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>

    <style>
    .perm-table{width:100%;border-collapse:collapse;background:var(--panel-2);border:1px solid var(--bd);border-radius:10px;overflow:hidden;}
    .perm-table th,.perm-table td{padding:11px 14px;text-align:left;border-bottom:1px solid var(--bd);font-size:14px;}
    .perm-table th{color:var(--mut);font-weight:500;font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    .perm-table th:not(:first-child),.perm-table td:not(:first-child){text-align:center;width:80px;}
    .perm-table tr:last-child td{border-bottom:0;}
    .pm-name{text-transform:capitalize;font-weight:500;}
    .perm-table input[type=checkbox]{width:18px;height:18px;accent-color:var(--acc);cursor:pointer;}
    </style>

    <script>
    // Write implies Read; unchecking Read clears Write
    document.querySelectorAll('[data-module-row]').forEach(function (row) {
        var read = row.querySelector('[data-read]');
        var write = row.querySelector('[data-write]');
        if (!read || !write) return;
        write.addEventListener('change', function () { if (write.checked) read.checked = true; });
        read.addEventListener('change', function () { if (!read.checked) write.checked = false; });
    });
    </script>
@endsection
