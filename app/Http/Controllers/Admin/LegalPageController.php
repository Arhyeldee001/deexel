<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalPageController extends Controller
{
    public function create()
    {
        return view('admin.legal.form', ['page' => new LegalPage(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        LegalPage::create($this->validateData($request));

        return redirect()->route('admin.pages.index')->with('ok', 'Legal page created.');
    }

    public function edit(LegalPage $legal)
    {
        return view('admin.legal.form', ['page' => $legal]);
    }

    public function update(Request $request, LegalPage $legal)
    {
        $legal->update($this->validateData($request, $legal->id)); // updated_at = "last updated"

        return redirect()->route('admin.pages.index')->with('ok', 'Legal page updated.');
    }

    public function destroy(LegalPage $legal)
    {
        $legal->delete();

        return redirect()->route('admin.pages.index')->with('ok', 'Legal page deleted.');
    }

    public function toggle(LegalPage $legal)
    {
        $legal->update(['is_active' => ! $legal->is_active]);

        return response()->json(['is_active' => $legal->is_active]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:190'],
            'slug'    => ['nullable', 'string', 'max:190', 'unique:legal_pages,slug'.($id ? ','.$id : '')],
            'content' => ['nullable', 'string'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
