@extends('admin.layouts.app')

@section('title', 'Blog categories')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif

    <div class="editor-top">
        <a href="{{ route('admin.posts.index') }}" class="back-link">← Back to posts</a>
    </div>

    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('admin.categories.store') }}" class="add-bar" style="max-width:520px;">
        @csrf
        <input type="text" name="name" placeholder="New category name…" required>
        <button type="submit" class="btn-acc">+ Add</button>
    </form>

    <div class="crud-list" style="max-width:520px;">
        @forelse($categories as $cat)
            <div class="sec-item">
                <div class="sec-head">
                    <span class="sec-name">{{ $cat->name }}</span>
                    <span class="badge">{{ $cat->posts_count }} post{{ $cat->posts_count === 1 ? '' : 's' }}</span>
                    <span class="spacer"></span>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category? Posts keep existing but lose this category.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No categories yet — add one above.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
