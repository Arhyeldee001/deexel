<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('sort_order')->get();

        return view('admin.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('admin.projects.form', [
            'project' => new Project(['is_active' => true, 'is_featured' => true]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = (int) Project::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        }

        $project = Project::create($data);
        $this->saveSeo($request, $project);

        return redirect()->route('admin.projects.index')->with('ok', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validateData($request, $project->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($project->featured_image) {
                Storage::disk('public')->delete($project->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('projects', 'public');
        } elseif ($request->boolean('remove_image') && $project->featured_image) {
            Storage::disk('public')->delete($project->featured_image);
            $data['featured_image'] = null;
        }

        $project->update($data);
        $this->saveSeo($request, $project);

        return redirect()->route('admin.projects.index')->with('ok', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        foreach ([$project->featured_image, $project->og_image] as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
        $project->delete();

        return back()->with('ok', 'Project deleted.');
    }

    public function toggle(Project $project)
    {
        $project->update(['is_active' => ! $project->is_active]);

        return response()->json(['is_active' => $project->is_active]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);
        foreach ($data['order'] as $position => $id) {
            Project::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:190'],
            'slug'             => ['nullable', 'string', 'max:190', 'unique:projects,slug'.($id ? ','.$id : '')],
            'category'         => ['nullable', 'string', 'max:120'],
            'client'           => ['nullable', 'string', 'max:120'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'body'             => ['nullable', 'string'],
            'project_url'      => ['nullable', 'url', 'max:255'],
            'completed_at'     => ['nullable', 'date'],
            'meta_title'       => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        return $data;
    }

    private function saveSeo(Request $request, Project $project): void
    {
        $request->validate([
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_image'      => ['nullable', 'image', 'max:4096'],
        ]);

        $project->meta_keywords = $request->input('meta_keywords');

        if ($request->hasFile('og_image')) {
            if ($project->og_image) {
                Storage::disk('public')->delete($project->og_image);
            }
            $project->og_image = $request->file('og_image')->store('seo', 'public');
        } elseif ($request->boolean('remove_og_image') && $project->og_image) {
            Storage::disk('public')->delete($project->og_image);
            $project->og_image = null;
        }

        $project->save();
    }
}
