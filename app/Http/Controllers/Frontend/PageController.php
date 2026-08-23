<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $page)
    {
        $model = Page::where('slug', $page)->where('is_active', true)->first();

        abort_if(! $model, 404);

        $sections = $model->activeSections()->orderBy('sort_order')->get();

        return view('frontend.pages.home', [
            'page'            => $model,
            'sections'        => $sections,
            'title'           => $model->slug === 'home' ? null : $model->title,
            'metaTitle'       => $model->meta_title,
            'metaDescription' => $model->meta_description,
            'metaKeywords'    => $model->meta_keywords,
            'ogImage'         => $model->og_image ? asset('storage/'.$model->og_image) : null,
        ]);
    }
}
