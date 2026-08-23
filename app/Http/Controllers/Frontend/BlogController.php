<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = PostCategory::has('posts')->orderBy('name')->get();

        $query = Post::published()->with('category')->latest('published_at');

        if ($request->filled('category')) {
            $cat = PostCategory::where('slug', $request->query('category'))->first();
            if ($cat) {
                $query->where('post_category_id', $cat->id);
            }
        }

        $posts = $query->paginate(9)->withQueryString();

        return view('frontend.pages.blog.index', [
            'posts'      => $posts,
            'categories' => $categories,
            'activeCat'  => $request->query('category'),
            'title'      => 'Blog',
        ]);
    }

    public function show(Post $post)
    {
        abort_unless($post->status === 'published', 404);

        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->post_category_id, fn ($q) => $q->where('post_category_id', $post->post_category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        $ogPath = $post->og_image ?: $post->featured_image;
        $ogUrl = $ogPath ? asset('storage/'.$ogPath) : null;

        $jsonLd = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $post->title,
            'description'   => $post->meta_description ?: $post->excerpt,
            'image'         => $ogUrl,
            'datePublished' => optional($post->published_at)->toIso8601String(),
            'dateModified'  => $post->updated_at->toIso8601String(),
            'author'        => [
                '@type' => 'Person',
                'name'  => optional($post->author)->name ?: Setting::get('site_name', 'Quin Tech'),
            ],
        ];

        return view('frontend.pages.blog.show', [
            'post'            => $post,
            'related'         => $related,
            'title'           => $post->title,
            'metaTitle'       => $post->meta_title ?: $post->title,
            'metaDescription' => $post->meta_description ?: $post->excerpt,
            'metaKeywords'    => $post->meta_keywords,
            'ogImage'         => $ogUrl,
            'jsonLd'          => $jsonLd,
        ]);
    }
}
