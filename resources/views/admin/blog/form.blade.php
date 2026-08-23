@extends('admin.layouts.app')

@section('title', $post->exists ? 'Edit post' : 'New post')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.posts.index') }}" class="back-link">← All posts</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
          enctype="multipart/form-data" class="crud-form" data-quill-form>
        @csrf
        @if($post->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required data-slug-source>
            </div>
            <div class="fld">
                <label>Slug <span class="hint">(auto from title if blank)</span></label>
                <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" data-slug-target>
            </div>

            <div class="fld">
                <label>Category</label>
                <select name="post_category_id">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) old('post_category_id', $post->post_category_id) === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fld">
                <label>Status</label>
                <select name="status">
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="fld full">
                <label>Excerpt <span class="hint">(shown on cards & meta description fallback)</span></label>
                <textarea name="excerpt" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div class="fld full">
                <label>Body</label>
                <input type="hidden" name="body" data-quill-target>
                <div class="quill-editor" data-quill>{!! old('body', $post->body) !!}</div>
            </div>

            <div class="fld full">
                <label>Featured image</label>
                @if($post->featured_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$post->featured_image) }}" alt="">
                        <label class="checkline"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*">
            </div>

            <div class="fld">
                <label>Publish date <span class="hint">(optional)</span></label>
                <input type="date" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d')) }}">
            </div>

            <div class="fld full"><label class="sub-label">SEO (optional)</label></div>
            <div class="fld">
                <label>Meta title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}">
            </div>
            <div class="fld">
                <label>Meta description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $post->meta_description) }}">
            </div>
            <div class="fld">
                <label>Meta keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" placeholder="e.g. laravel, tutorial">
            </div>
            <div class="fld full">
                <label>Social share image (OG) <span class="hint">(falls back to the featured image if empty)</span></label>
                @if($post->og_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$post->og_image) }}" alt="" style="max-height:70px;border-radius:8px;">
                        <label class="checkline"><input type="checkbox" name="remove_og_image" value="1"> Remove</label>
                    </div>
                @endif
                <input type="file" name="og_image" accept="image/*">
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $post->exists ? 'Save changes' : 'Create post' }}</button>
                <a href="{{ route('admin.posts.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
