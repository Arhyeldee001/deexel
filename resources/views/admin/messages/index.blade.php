@extends('admin.layouts.app')

@section('title', 'Messages')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif

    <div class="crud-top">
        <p class="muted-lead">Enquiries from your contact form. Click one to read it.</p>
        @if($messages->where('is_read', false)->count())
            <form method="POST" action="{{ route('admin.messages.readAll') }}">
                @csrf
                <button type="submit" class="btn-sm ghost">Mark all read</button>
            </form>
        @endif
    </div>

    <div class="crud-list">
        @forelse($messages as $message)
            <a href="{{ route('admin.messages.show', $message) }}" class="sec-item msg-row {{ $message->is_read ? '' : 'unread' }}" style="text-decoration:none;">
                <div class="sec-head">
                    <span class="unread-dot"></span>
                    <span class="sec-name">{{ $message->name }}</span>
                    <span class="badge">{{ $message->email }}</span>
                    @if($message->subject)<span class="msg-subject">{{ $message->subject }}</span>@endif
                    <span class="spacer"></span>
                    <span class="post-date">{{ $message->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @empty
            <div class="empty">No messages yet. Submissions from your <a href="{{ route('contact.index') }}" target="_blank">contact form</a> will appear here.</div>
        @endforelse
    </div>

    <style>
    .msg-row .unread-dot{width:8px;height:8px;border-radius:50%;background:transparent;flex:none;}
    .msg-row.unread .unread-dot{background:var(--acc);box-shadow:0 0 8px var(--acc);}
    .msg-row.unread .sec-name{font-weight:700;}
    .msg-row:not(.unread){opacity:.72;}
    .msg-subject{font-size:13px;color:var(--mut);}
    </style>
@endsection
