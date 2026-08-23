<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.pages.projects.index', [
            'projects' => $projects,
            'title'    => 'Projects',
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->is_active, 404);

        return view('frontend.pages.projects.show', [
            'project'         => $project,
            'title'           => $project->title,
            'metaTitle'       => $project->meta_title ?: $project->title,
            'metaDescription' => $project->meta_description ?: $project->excerpt,
            'metaKeywords'    => $project->meta_keywords,
            'ogImage'         => $project->og_image ? asset('storage/'.$project->og_image) : null,
        ]);
    }
}
