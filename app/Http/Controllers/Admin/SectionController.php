<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Page $page)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(PageController::sectionTypes()))],
        ]);

        $max = (int) $page->sections()->max('sort_order');

        $page->sections()->create([
            'type'       => $data['type'],
            'name'       => PageController::sectionTypes()[$data['type']],
            'content'    => [],
            'sort_order' => $max + 1,
            'is_active'  => true,
        ]);

        return back()->with('ok', 'Section added.');
    }

    public function update(Request $request, Section $section)
    {
        $content = $section->content ?? [];
        $in = $request->input('content', []);

        // Normalise repeatable groups (drop empty rows, re-index)
        if (isset($in['items'])) {
            $in['items'] = array_values(array_filter($in['items'], fn ($r) => ($r['value'] ?? '') !== '' || ($r['label'] ?? '') !== ''));
        }
        if (isset($in['rows'])) {
            $in['rows'] = array_values(array_filter($in['rows'], fn ($r) => ($r['title'] ?? '') !== '' || ($r['text'] ?? '') !== '' || ($r['icon'] ?? '') !== ''));
        }
        if (isset($in['checks'])) {
            $in['checks'] = array_values(array_filter($in['checks'], fn ($v) => trim((string) $v) !== ''));
        }

        $content = array_merge($content, $in);

        if ($request->hasFile('image')) {
            $request->validate(['image' => ['image', 'max:4096']]);
            $content['image'] = $request->file('image')->store('sections', 'public');
        }

        if ($request->boolean('remove_image')) {
            $content['image'] = null;
        }

        $section->update(['content' => $content]);

        return back()->with('ok', 'Section “'.$section->name.'” saved.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return back()->with('ok', 'Section removed.');
    }

    public function toggle(Section $section)
    {
        $section->update(['is_active' => ! $section->is_active]);

        return response()->json(['is_active' => $section->is_active]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($data['order'] as $position => $id) {
            Section::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
