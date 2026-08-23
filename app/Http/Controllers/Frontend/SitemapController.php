<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        $reserved = ['home', 'services', 'projects', 'team', 'blog', 'faq', 'contact'];

        $urls[] = ['loc' => route('home'), 'priority' => '1.0'];
        foreach (['services.index', 'projects.index', 'team.index', 'blog.index', 'faq.index', 'contact.index'] as $r) {
            $urls[] = ['loc' => route($r), 'priority' => '0.8'];
        }

        // Custom CMS pages (e.g. About) not already covered above
        foreach (Page::where('is_active', true)->get() as $p) {
            if (! in_array($p->slug, $reserved, true)) {
                $urls[] = ['loc' => url($p->slug), 'priority' => '0.6'];
            }
        }

        foreach (Service::where('is_active', true)->orderBy('sort_order')->get() as $s) {
            $urls[] = ['loc' => route('services.show', $s), 'priority' => '0.7'];
        }
        foreach (Project::where('is_active', true)->orderBy('sort_order')->get() as $p) {
            $urls[] = ['loc' => route('projects.show', $p), 'priority' => '0.7'];
        }
        foreach (Post::published()->latest('published_at')->get() as $p) {
            $urls[] = ['loc' => route('blog.show', $p), 'lastmod' => $p->updated_at->toAtomString(), 'priority' => '0.6'];
        }
        foreach (LegalPage::where('is_active', true)->get() as $l) {
            $urls[] = ['loc' => route('legal.show', $l->slug), 'lastmod' => $l->updated_at->toAtomString(), 'priority' => '0.3'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.htmlspecialchars($u['loc'], ENT_XML1).'</loc>'."\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>'.$u['lastmod'].'</lastmod>'."\n";
            }
            if (! empty($u['priority'])) {
                $xml .= '    <priority>'.$u['priority'].'</priority>'."\n";
            }
            $xml .= '  </url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
