<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;

class LegalController extends Controller
{
    public function show(string $slug)
    {
        $page = LegalPage::where('slug', $slug)->where('is_active', true)->first();

        abort_if(! $page, 404);

        return view('frontend.pages.legal', [
            'page'  => $page,
            'title' => $page->title,
        ]);
    }
}
