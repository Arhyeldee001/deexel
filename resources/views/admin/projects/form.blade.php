@extends('admin.layouts.app')

@section('title', $project->exists ? 'Edit project' : 'New project')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.projects.index') }}" class="back-link">← All projects</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
          enctype="multipart/form-data" class="crud-form" data-quill-form>
        @csrf
        @if($project->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $project->title) }}" required data-slug-source>
            </div>
            <div class="fld">
                <label>Slug <span class="hint">(auto from title if blank)</span></label>
                <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" data-slug-target>
            </div>

            <div class="fld">
                <label>Category</label>
                <input type="text" name="category" value="{{ old('category', $project->category) }}" placeholder="Fintech">
            </div>
            <div class="fld">
                <label>Client</label>
                <input type="text" name="client" value="{{ old('client', $project->client) }}">
            </div>

            <div class="fld">
                <label>Project URL</label>
                <input type="text" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="https://…">
            </div>
            <div class="fld">
                <label>Completed on</label>
                <input type="date" name="completed_at" value="{{ old('completed_at', optional($project->completed_at)->format('Y-m-d')) }}">
            </div>

            <div class="fld full">
                <label>Excerpt</label>
                <textarea name="excerpt" rows="2">{{ old('excerpt', $project->excerpt) }}</textarea>
            </div>

            <div class="fld full">
                <label>Case study / description</label>
                <input type="hidden" name="body" data-quill-target>
                <div class="quill-editor" data-quill>{!! old('body', $project->body) !!}</div>
            </div>

            <div class="fld full">
                <label>Featured image</label>
                @if($project->featured_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$project->featured_image) }}" alt="">
                        <label class="checkline"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*">
            </div>

            <label class="checkline">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->is_active) ? 'checked' : '' }}> Live on the site
            </label>
            <label class="checkline">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}> Featured on homepage
            </label>

            <div class="fld full"><label class="sub-label">SEO (optional)</label></div>
            <div class="fld">
                <label>Meta title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $project->meta_title) }}">
            </div>
            <div class="fld">
                <label>Meta description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $project->meta_description) }}">
            </div>
            <div class="fld">
                <label>Meta keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $project->meta_keywords) }}" placeholder="e.g. fintech, mobile app">
            </div>
            <div class="fld full">
                <label>Social share image (OG) <span class="hint">(shown when this page is shared)</span></label>
                @if($project->og_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$project->og_image) }}" alt="" style="max-height:70px;border-radius:8px;">
                        <label class="checkline"><input type="checkbox" name="remove_og_image" value="1"> Remove</label>
                    </div>
                @endif
                <input type="file" name="og_image" accept="image/*">
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $project->exists ? 'Save changes' : 'Create project' }}</button>
                <a href="{{ route('admin.projects.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
