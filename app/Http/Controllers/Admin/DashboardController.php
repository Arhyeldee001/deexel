<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $can  = fn (string $p) => $user && $user->can($p);

        $stats = [];

        if ($can('view pages')) {
            $stats[] = ['label' => 'Pages', 'value' => Page::count(), 'sub' => Page::where('is_active', true)->count().' live', 'url' => route('admin.pages.index')];
        }
        if ($can('view services')) {
            $stats[] = ['label' => 'Services', 'value' => Service::count(), 'sub' => Service::where('is_active', true)->count().' live', 'url' => route('admin.services.index')];
        }
        if ($can('view projects')) {
            $stats[] = ['label' => 'Projects', 'value' => Project::count(), 'sub' => Project::where('is_active', true)->count().' live', 'url' => route('admin.projects.index')];
        }
        if ($can('view blog')) {
            $stats[] = ['label' => 'Blog posts', 'value' => Post::count(), 'sub' => Post::where('status', 'published')->count().' published', 'url' => route('admin.posts.index')];
        }
        if ($can('view team')) {
            $stats[] = ['label' => 'Team', 'value' => TeamMember::count(), 'sub' => 'members', 'url' => route('admin.team.index')];
        }
        if ($can('view faqs')) {
            $stats[] = ['label' => 'FAQs', 'value' => Faq::count(), 'sub' => 'questions', 'url' => route('admin.faqs.index')];
        }
        if ($can('view messages')) {
            $unread = ContactSubmission::where('is_read', false)->count();
            $stats[] = ['label' => 'Messages', 'value' => ContactSubmission::count(), 'sub' => $unread.' unread', 'url' => route('admin.messages.index'), 'alert' => $unread > 0];
        }

        return view('admin.dashboard', [
            'stats'          => $stats,
            'recentMessages' => $can('view messages') ? ContactSubmission::latest()->take(5)->get() : collect(),
            'recentPosts'    => $can('view blog') ? Post::latest('id')->take(5)->get() : collect(),
            'maintenance'    => filter_var(Setting::get('maintenance_mode', false), FILTER_VALIDATE_BOOLEAN),
            'canSettings'    => $can('manage settings'),
        ]);
    }
}
