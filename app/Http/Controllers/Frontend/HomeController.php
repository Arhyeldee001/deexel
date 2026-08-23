<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        $sections = $page->activeSections()->orderBy('sort_order')->get();

        return view('frontend.pages.home', [
            'page'            => $page,
            'sections'        => $sections,
            'title'           => null, // home shows just the site name
            'metaTitle'       => $page->meta_title,
            'metaDescription' => $page->meta_description,
            'metaKeywords'    => $page->meta_keywords,
            'ogImage'         => $page->og_image,
        ]);
    }
}
