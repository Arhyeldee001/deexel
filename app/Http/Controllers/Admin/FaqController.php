<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->get();

        return view('admin.faqs.index', ['faqs' => $faqs]);
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = (int) Faq::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active');

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('ok', 'FAQ added.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', ['faq' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('ok', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('ok', 'FAQ removed.');
    }

    public function toggle(Faq $faq)
    {
        $faq->update(['is_active' => ! $faq->is_active]);

        return response()->json(['is_active' => $faq->is_active]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);
        foreach ($data['order'] as $position => $id) {
            Faq::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer'   => ['required', 'string', 'max:4000'],
            'category' => ['nullable', 'string', 'max:120'],
        ]);
    }
}
