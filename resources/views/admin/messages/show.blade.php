@extends('admin.layouts.app')

@section('title', 'Message')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    <div class="editor-top">
        <a href="{{ route('admin.messages.index') }}" class="back-link">← All messages</a>
    </div>

    <div class="msg-card">
        <div class="msg-head">
            <div>
                <h2 style="font-size:19px;">{{ $message->name }}</h2>
                <a href="mailto:{{ $message->email }}" style="color:var(--acc);font-size:14px;">{{ $message->email }}</a>
                @if($message->phone)<span class="mut" style="font-size:14px;"> · {{ $message->phone }}</span>@endif
            </div>
            <span class="post-date">{{ $message->created_at->format('d M Y, H:i') }}</span>
        </div>

        @if($message->subject)
            <div class="msg-field"><span class="mut">Subject</span><div>{{ $message->subject }}</div></div>
        @endif

        <div class="msg-field"><span class="mut">Message</span><div class="msg-body">{{ $message->message }}</div></div>

        @if($message->ip_address)<p class="mut" style="font-size:12px;">Sent from IP {{ $message->ip_address }}</p>@endif

        <div class="msg-actions">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?: 'Your enquiry' }}" class="btn-acc">Reply by email</a>
            <button type="button" class="btn-sm" data-toggle-url="{{ route('admin.messages.toggleRead', $message) }}" id="readBtn">
                {{ $message->is_read ? 'Mark unread' : 'Mark read' }}
            </button>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')" style="margin-left:auto;">
                @csrf @method('DELETE')
                <button type="submit" class="btn-sm ghost danger-text">Delete</button>
            </form>
        </div>
    </div>

    <style>
    .msg-card{background:var(--panel);border:1px solid var(--bd);border-radius:14px;padding:24px;max-width:720px;}
    .msg-head{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;border-bottom:1px solid var(--bd);padding-bottom:16px;margin-bottom:16px;}
    .msg-field{margin-bottom:16px;}
    .msg-field .mut{font-size:12px;text-transform:uppercase;letter-spacing:.05em;}
    .msg-body{white-space:pre-wrap;line-height:1.7;margin-top:4px;}
    .msg-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-top:20px;border-top:1px solid var(--bd);padding-top:18px;}
    .danger-text{color:#fca5a5;}
    </style>

    <script>
    // Toggle read/unread without leaving the page
    (function () {
        var btn = document.getElementById('readBtn');
        if (!btn) return;
        var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        btn.addEventListener('click', function () {
            fetch(btn.dataset.toggleUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            })
            .then(function (r) { return r.json(); })
            .then(function (d) { btn.textContent = d.is_read ? 'Mark unread' : 'Mark read'; })
            .catch(function () {});
        });
    })();
    </script>
@endsection
