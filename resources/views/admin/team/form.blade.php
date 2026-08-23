@extends('admin.layouts.app')

@section('title', $member->exists ? 'Edit member' : 'New member')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.team.index') }}" class="back-link">← All team members</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}"
          enctype="multipart/form-data" class="crud-form">
        @csrf
        @if($member->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" required>
            </div>
            <div class="fld">
                <label>Role</label>
                <input type="text" name="role" value="{{ old('role', $member->role) }}" placeholder="Lead Engineer">
            </div>

            <div class="fld full">
                <label>Bio</label>
                <textarea name="bio" rows="3">{{ old('bio', $member->bio) }}</textarea>
            </div>

            <div class="fld full">
                <label>Photo</label>
                @if($member->photo)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$member->photo) }}" alt="" style="width:110px;height:110px;object-fit:cover;border-radius:12px;">
                        <label class="checkline"><input type="checkbox" name="remove_photo" value="1"> Remove current photo</label>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*">
            </div>

            <div class="fld full"><label class="sub-label">Social links (optional)</label></div>
            @php $s = old('socials', $member->socials ?? []); @endphp
            <div class="fld">
                <label>LinkedIn</label>
                <input type="text" name="socials[linkedin]" value="{{ $s['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/…">
            </div>
            <div class="fld">
                <label>X (Twitter)</label>
                <input type="text" name="socials[x]" value="{{ $s['x'] ?? '' }}" placeholder="https://x.com/…">
            </div>
            <div class="fld">
                <label>GitHub</label>
                <input type="text" name="socials[github]" value="{{ $s['github'] ?? '' }}" placeholder="https://github.com/…">
            </div>
            <div class="fld">
                <label>Website</label>
                <input type="text" name="socials[website]" value="{{ $s['website'] ?? '' }}" placeholder="https://…">
            </div>

            <label class="checkline full">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }}> Visible on the site
            </label>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $member->exists ? 'Save changes' : 'Add member' }}</button>
                <a href="{{ route('admin.team.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection
