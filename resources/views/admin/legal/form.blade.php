@extends('admin.layouts.app')

@section('title', $page->exists ? 'Edit legal page' : 'New legal page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.pages.index') }}" class="back-link">← Back to pages</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $page->exists ? route('admin.legal.update', $page) : route('admin.legal.store') }}"
          class="crud-form" data-quill-form>
        @csrf
        @if($page->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required data-slug-source>
            </div>
            <div class="fld">
                <label>Slug <span class="hint">(auto from title if blank)</span></label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" data-slug-target>
            </div>

            <div class="fld full">
                <label>Content</label>
                <input type="hidden" name="content" data-quill-target>
                <div class="quill-editor" data-quill>{!! old('content', $page->content) !!}</div>
            </div>

            <label class="checkline full">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}> Active (show in footer)
            </label>

            @if($page->exists)
                <p class="mut full" style="font-size:13px;">Last updated {{ $page->updated_at->format('F j, Y \a\t H:i') }}</p>
            @endif

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $page->exists ? 'Save changes' : 'Create page' }}</button>
                <a href="{{ route('admin.pages.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
