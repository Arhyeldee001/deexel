<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest('published_at')->latest('id')->get();

        return view('admin.blog.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('admin.blog.form', [
            'post'       => new Post(['status' => 'draft']),
            'categories' => PostCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['user_id'] = $request->user()->id;
        $data = $this->applyStatus($request, $data, null);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $post = Post::create($data);
        $this->saveSeo($request, $post);

        return redirect()->route('admin.posts.index')->with('ok', 'Post created.');
    }

    public function edit(Post $post)
    {
        return view('admin.blog.form', [
            'post'       => $post,
            'categories' => PostCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validateData($request, $post->id);
        $data = $this->applyStatus($request, $data, $post);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } elseif ($request->boolean('remove_image') && $post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
            $data['featured_image'] = null;
        }

        $post->update($data);
        $this->saveSeo($request, $post);

        return redirect()->route('admin.posts.index')->with('ok', 'Post updated.');
    }

    public function destroy(Post $post)
    {
        foreach ([$post->featured_image, $post->og_image] as $file) {
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
        $post->delete();

        return back()->with('ok', 'Post deleted.');
    }

    public function toggle(Post $post)
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft']);
        } else {
            $post->update([
                'status'       => 'published',
                'published_at' => $post->published_at ?? now(),
            ]);
        }

        return response()->json(['is_active' => $post->status === 'published']);
    }

    private function applyStatus(Request $request, array $data, ?Post $post): array
    {
        $status = $request->input('status', 'draft');
        $data['status'] = in_array($status, ['draft', 'published'], true) ? $status : 'draft';

        $publishedAt = $request->input('published_at');
        if ($publishedAt) {
            $data['published_at'] = $publishedAt;
        } elseif ($data['status'] === 'published') {
            $data['published_at'] = $post?->published_at ?? now();
        }

        return $data;
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:190'],
            'slug'             => ['nullable', 'string', 'max:190', 'unique:posts,slug'.($id ? ','.$id : '')],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'body'             => ['nullable', 'string'],
            'status'           => ['nullable', 'in:draft,published'],
            'published_at'     => ['nullable', 'date'],
            'meta_title'       => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        return $data;
    }

    private function saveSeo(Request $request, Post $post): void
    {
        $request->validate([
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'og_image'      => ['nullable', 'image', 'max:4096'],
        ]);

        $post->meta_keywords = $request->input('meta_keywords');

        if ($request->hasFile('og_image')) {
            if ($post->og_image) {
                Storage::disk('public')->delete($post->og_image);
            }
            $post->og_image = $request->file('og_image')->store('seo', 'public');
        } elseif ($request->boolean('remove_og_image') && $post->og_image) {
            Storage::disk('public')->delete($post->og_image);
            $post->og_image = null;
        }

        $post->save();
    }
}
