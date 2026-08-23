@extends('admin.layouts.app')

@section('title', $service->exists ? 'Edit service' : 'New service')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.services.index') }}" class="back-link">← All services</a>
    </div>

    @if($errors->any())
        <div class="form-errors">{{ $errors->first() }}</div>
    @endif

    <form method="POST"
          action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
          enctype="multipart/form-data" class="crud-form" data-quill-form>
        @csrf
        @if($service->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" required data-slug-source>
            </div>
            <div class="fld">
                <label>Slug <span class="hint">(auto from title if blank)</span></label>
                <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" data-slug-target>
            </div>

            <div class="fld">
                <label>Icon</label>
                <select name="icon">
                    @foreach($icons as $ic)
                        <option value="{{ $ic }}" {{ old('icon', $service->icon) === $ic ? 'selected' : '' }}>{{ $ic }}</option>
                    @endforeach
                </select>
            </div>
            <label class="checkline align-end">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}> Live on the site
            </label>

            <div class="fld full">
                <label>Excerpt <span class="hint">(short line shown on cards)</span></label>
                <textarea name="excerpt" rows="2">{{ old('excerpt', $service->excerpt) }}</textarea>
            </div>

            <div class="fld full">
                <label>Full description</label>
                <input type="hidden" name="body" data-quill-target>
                <div class="quill-editor" data-quill>{!! old('body', $service->body) !!}</div>
            </div>

            <div class="fld full">
                <label>Image <span class="hint">(optional)</span></label>
                @if($service->image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$service->image) }}" alt="">
                        <label class="checkline"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="fld full"><label class="sub-label">SEO (optional)</label></div>
            <div class="fld">
                <label>Meta title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}">
            </div>
            <div class="fld">
                <label>Meta description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $service->meta_description) }}">
            </div>
            <div class="fld">
                <label>Meta keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $service->meta_keywords) }}" placeholder="e.g. web design, laravel">
            </div>
            <div class="fld full">
                <label>Social share image (OG) <span class="hint">(shown when this page is shared)</span></label>
                @if($service->og_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$service->og_image) }}" alt="" style="max-height:70px;border-radius:8px;">
                        <label class="checkline"><input type="checkbox" name="remove_og_image" value="1"> Remove</label>
                    </div>
                @endif
                <input type="file" name="og_image" accept="image/*">
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $service->exists ? 'Save changes' : 'Create service' }}</button>
                <a href="{{ route('admin.services.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
