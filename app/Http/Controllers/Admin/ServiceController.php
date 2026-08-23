<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public static array $icons = ['code', 'mobile', 'cloud', 'design', 'layers', 'shield', 'rocket', 'chart', 'chat'];

    public function index()
    {
        $services = Service::orderBy('sort_order')->get();

        return view('admin.services.index', ['services' => $services]);
    }

    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service(['is_active' => true, 'icon' => 'code']),
            'icons'   => self::$icons,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = (int) Service::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);
        $this->saveSeo($request, $service);

        return redirect()->route('admin.services.index')->with('ok', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['service' => $service, 'icons' => self::$icons]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $this->validateData($request, $service->id);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        } elseif ($request->boolean('remove_image') && $service->image) {
            Storage::disk('public')->delete($service->image);
            $data['image'] = null;
        }

        $service->update($data);
        $this->saveSeo($request, $service);

        return redirect()->route('admin.services.index')->with('ok', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        foreach ([$service->image, $service->og_image] as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
        $service->delete();

        return back()->with('ok', 'Service deleted.');
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        return response()->json(['is_active' => $service->is_active]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);
        foreach ($data['order'] as $position => $id) {
            Service::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:190'],
            'slug'             => ['nullable', 'string', 'max:190', 'unique:services,slug'.($id ? ','.$id : '')],
            'icon'             => ['nullable', 'string', 'max:40'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'body'             => ['nullable', 'string'],
            'meta_title'       => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        return $data;
    }

    /** Persist SEO extras via direct assignment so no model fillable change is needed. */
    private function saveSeo(Request $request, Service $service): void
    {
        $request->validate([
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_image'      => ['nullable', 'image', 'max:4096'],
        ]);

        $service->meta_keywords = $request->input('meta_keywords');

        if ($request->hasFile('og_image')) {
            if ($service->og_image) {
                Storage::disk('public')->delete($service->og_image);
            }
            $service->og_image = $request->file('og_image')->store('seo', 'public');
        } elseif ($request->boolean('remove_og_image') && $service->og_image) {
            Storage::disk('public')->delete($service->og_image);
            $service->og_image = null;
        }

        $service->save();
    }
}
