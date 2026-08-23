<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.pages.services.index', [
            'services' => $services,
            'title'    => 'Services',
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('frontend.pages.services.show', [
            'service'         => $service,
            'title'           => $service->title,
            'metaTitle'       => $service->meta_title ?: $service->title,
            'metaDescription' => $service->meta_description ?: $service->excerpt,
            'metaKeywords'    => $service->meta_keywords,
            'ogImage'         => $service->og_image ? asset('storage/'.$service->og_image) : null,
        ]);
    }
}
