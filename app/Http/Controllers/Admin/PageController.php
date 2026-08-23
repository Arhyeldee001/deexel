<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public static function sectionTypes(): array
    {
        return [
            'hero'        => 'Hero',
            'services'    => 'Services',
            'stats'       => 'Stats',
            'features'    => 'Features',
            'projects'    => 'Projects',
            'blog_teaser' => 'Insights / Blog',
            'cta'         => 'Call to action',
        ];
    }

    public function index()
    {
        $pages = Page::withCount('sections')->orderBy('sort_order')->get();
        $legalPages = LegalPage::orderBy('title')->get();

        return view('admin.pages.index', ['pages' => $pages, 'legalPages' => $legalPages]);
    }

    public function edit(Page $page)
    {
        $page->load(['sections' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.pages.edit', [
            'page'  => $page,
            'types' => self::sectionTypes(),
        ]);
    }

    public function update(Request $request, Page $page)
    {
        // SEO now lives under Settings → SEO; this only manages structure.
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:190'],
            'slug'        => ['required', 'string', 'max:190', 'unique:pages,slug,'.$page->id],
            'show_in_nav' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['slug']);
        $data['show_in_nav'] = $request->boolean('show_in_nav');

        $page->update($data);

        return back()->with('ok', 'Page settings saved.');
    }

    public function destroy(Page $page)
    {
        if ($page->is_system) {
            return back()->with('error', 'Core pages can’t be deleted — toggle them off instead.');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')->with('ok', 'Page deleted.');
    }

    public function toggle(Page $page)
    {
        $page->update(['is_active' => ! $page->is_active]);

        return response()->json(['is_active' => $page->is_active]);
    }

    public function toggleNav(Page $page)
    {
        $page->update(['show_in_nav' => ! $page->show_in_nav]);

        return response()->json(['show_in_nav' => $page->show_in_nav]);
    }
}
