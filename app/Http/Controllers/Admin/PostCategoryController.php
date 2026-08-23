<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::withCount('posts')->orderBy('name')->get();

        return view('admin.blog.categories', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120']]);
        $data['slug'] = Str::slug($data['name']);

        PostCategory::firstOrCreate(['slug' => $data['slug']], $data);

        return back()->with('ok', 'Category added.');
    }

    public function destroy(PostCategory $category)
    {
        $category->delete();

        return back()->with('ok', 'Category deleted.');
    }
}
