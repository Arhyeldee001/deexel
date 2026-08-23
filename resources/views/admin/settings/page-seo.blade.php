@extends('admin.layouts.app')

@section('title', 'SEO: '.$page->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.settings.edit') }}" class="back-link">← Back to settings</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <h2 style="font-size:19px;margin-bottom:4px;">SEO — {{ $page->title }}</h2>
    <p class="mut" style="margin-bottom:20px;font-size:14px;">/{{ $page->slug }} · leave a field blank to fall back to the site defaults.</p>

    <form method="POST" action="{{ route('admin.settings.pageSeo.update', $page) }}" enctype="multipart/form-data" class="crud-form" style="max-width:640px;">
        @csrf @method('PUT')

        <div class="form-grid">
            <div class="fld full">
                <label>Meta title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
            </div>
            <div class="fld full">
                <label>Meta description</label>
                <textarea name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>
            <div class="fld full">
                <label>Meta keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="e.g. software agency, lagos">
            </div>
            <div class="fld full">
                <label>Social share image (OG)</label>
                @if($page->og_image)
                    <div class="img-preview">
                        <img src="{{ asset('storage/'.$page->og_image) }}" alt="" style="max-height:70px;border-radius:8px;">
                        <label class="checkline"><input type="checkbox" name="remove_og_image" value="1"> Remove</label>
                    </div>
                @endif
                <input type="file" name="og_image" accept="image/*">
            </div>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">Save SEO</button>
                <a href="{{ route('admin.settings.edit') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection
