@php
    use App\Models\Setting;
    use App\Models\ContactSubmission;
    $siteName = Setting::get('site_name', config('app.name', 'Quin Tech'));
    $unreadCount = auth()->user()?->can('view messages') ? ContactSubmission::where('is_read', false)->count() : 0;

    $accType  = Setting::get('accent_type', 'solid');
    $acc1     = Setting::get('accent_color', '#ff6a00');
    $acc2     = Setting::get('accent_color_2', $acc1);
    $accAngle = (int) Setting::get('accent_angle', 90);
    $accGrad  = $accType === 'gradient' ? "linear-gradient({$accAngle}deg, {$acc1}, {$acc2})" : $acc1;
    $favicon  = Setting::get('favicon');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') — {{ $siteName }} Admin</title>
    @if($favicon)<link rel="icon" href="{{ asset('storage/'.$favicon) }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-confirm.css') }}">
    <style>
        :root{ --acc: {{ $acc1 }}; --acc-2: {{ $acc2 }}; --acc-grad: {{ $accGrad }}; }
        .btn-acc{ background: var(--acc-grad); }
        .nav-badge{margin-left:auto;background:var(--acc);color:#22160a;font-size:11px;font-weight:600;border-radius:999px;padding:1px 8px;}
        .nav-sep{height:1px;background:var(--bd);margin:8px 6px;}
    </style>
    @stack('styles')
</head>
<body>
<div class="shell" id="shell">
    <div class="backdrop" onclick="document.getElementById('shell').classList.remove('nav-open')"></div>

    <aside class="sidebar">
        <div class="brand">{{ strtoupper($siteName) }}<span class="dot">.</span></div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        @can('view pages')<a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.legal.*') ? 'active' : '' }}">Pages &amp; sections</a>@endcan
        @can('view services')<a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>@endcan
        @can('view projects')<a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">Projects</a>@endcan
        @can('view team')<a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}">Team</a>@endcan
        @can('view faqs')<a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">FAQs</a>@endcan
        @can('view blog')<a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">Blog</a>@endcan
        @can('view messages')
        <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" style="display:flex;align-items:center;">
            Messages
            @if($unreadCount)<span class="nav-badge">{{ $unreadCount }}</span>@endif
        </a>
        @endcan
        @canany(['view settings', 'view users', 'view roles'])<div class="nav-sep"></div>@endcanany
        @can('view settings')<a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a>@endcan
        @can('view users')<a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>@endcan
        @can('view roles')<a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles</a>@endcan

        <div class="grow"></div>
        <div class="foot">
            <a href="{{ route('home') }}" target="_blank">View site ↗</a>
            <form method="POST" action="{{ route('admin.logout') }}" data-confirm="Sign out of the admin panel?" style="margin-top:6px;">
                @csrf
                <button type="submit" class="logout-btn" style="width:100%;">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="content">
        <div class="topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="mobile-only" onclick="document.getElementById('shell').classList.toggle('nav-open')">☰</button>
                <h1>@yield('title', 'Dashboard')</h1>
            </div>
            <span class="who">{{ auth()->user()?->name }}</span>
        </div>
        <div class="main">
            @yield('content')
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>
<script src="{{ asset('js/admin-confirm.js') }}"></script>
@stack('scripts')
</body>
</html>
