@extends('admin.layouts.app')

@section('title', 'Blog')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Write, publish, and manage your articles. Toggle a post between draft and live.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.categories.index') }}" class="btn-sm ghost">Manage categories</a>
            <a href="{{ route('admin.posts.create') }}" class="btn-acc">+ New post</a>
        </div>
    </div>

    <div class="crud-list">
        @forelse($posts as $post)
            <div class="sec-item {{ $post->status === 'published' ? '' : 'off' }}" data-id="{{ $post->id }}">
                <div class="sec-head">
                    @if($post->featured_image)
                        <span class="thumb-img"><img src="{{ asset('storage/'.$post->featured_image) }}" alt=""></span>
                    @else
                        <span class="thumb-ico">@include('frontend.partials.icon', ['name' => 'chat'])</span>
                    @endif
                    <span class="sec-name">{{ $post->title }}</span>
                    @if($post->category)<span class="badge">{{ $post->category->name }}</span>@endif
                    <span class="status-tag {{ $post->status }}">{{ $post->status }}</span>
                    <span class="spacer"></span>
                    <span class="post-date">{{ optional($post->published_at)->format('d M Y') }}</span>

                    <label class="toggle" title="Published on/off">
                        <input type="checkbox" data-toggle-url="{{ route('admin.posts.toggle', $post) }}" {{ $post->status === 'published' ? 'checked' : '' }}>
                        <span class="track"></span>
                    </label>

                    <a href="{{ route('admin.posts.edit', $post) }}" class="icon-btn" title="Edit">✎</a>

                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="icon-btn danger" title="Delete">🗑</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty">No posts yet. <a href="{{ route('admin.posts.create') }}">Write your first article →</a></div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-crud.js') }}"></script>
@endpush
