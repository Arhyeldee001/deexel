@extends('admin.layouts.app')

@section('title', $faq->exists ? 'Edit FAQ' : 'New FAQ')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.faqs.index') }}" class="back-link">← All FAQs</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST"
          action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
          class="crud-form">
        @csrf
        @if($faq->exists) @method('PUT') @endif

        <div class="form-grid">
            <div class="fld full">
                <label>Question</label>
                <input type="text" name="question" value="{{ old('question', $faq->question) }}" required>
            </div>
            <div class="fld full">
                <label>Answer</label>
                <textarea name="answer" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
            </div>
            <div class="fld">
                <label>Category <span class="hint">(optional — groups on the site)</span></label>
                <input type="text" name="category" value="{{ old('category', $faq->category) }}" placeholder="Pricing">
            </div>
            <label class="checkline align-end">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}> Show on the site
            </label>

            <div class="full form-actions">
                <button type="submit" class="btn-acc">{{ $faq->exists ? 'Save changes' : 'Add FAQ' }}</button>
                <a href="{{ route('admin.faqs.index') }}" class="btn-sm ghost">Cancel</a>
            </div>
        </div>
    </form>
@endsection
