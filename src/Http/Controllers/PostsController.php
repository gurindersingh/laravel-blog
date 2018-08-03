<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Illuminate\Http\Request;
use Gurinder\LaravelBlog\Models\Tag;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Category;
use Gurinder\LaravelBlog\Http\Requests\PostRequest;
use TomLingham\Searchy\Facades\Searchy;

class PostsController extends Controller
{

    public function index(Request $request)
    {
        $searchingFor = $request->search ? "Showing results for <strong>{$request->search}</strong>" : null;

        $posts = Post::post();

        if ($request->search) {
            $posts = Post::where('id', 'like', "%{$request->search}%")->orWhere('title', 'like', "%{$request->search}%")->post();
        }

        $posts = $posts->with(['featuredImage', 'category', 'tags', 'author'])->paginate($request->perPage ?: 20);

        return view('gblog::posts.index', compact('posts', 'searchingFor'));
    }

    public function create()
    {
        $categories = Category::whereParentId(null)->with('children')->get();

        $tags = Tag::get();

        return view('gblog::posts.create', compact('categories', 'tags'));
    }


    public function edit(Request $request, $id)
    {
        $post = Post::post()->whereId($id)->with(['featuredImage', 'media', 'category', 'tags'])->firstOrFail();

        $categories = Category::whereParentId(null)->with('children')->get();

        return view('gblog::posts.edit', compact('post', 'categories'));
    }

    public function store(PostRequest $request)
    {
        return $request->persist();
    }

    public function update(PostRequest $request, $id)
    {
        return $request->update($id);
    }

    public function destroy($id)
    {
        $post = Post::whereId($id)->with(['featuredImage', 'media'])->firstOrFail();

        if ($post->author_id == auth()->id()) {

            $post->delete();

            $disk = \Storage::disk(config('media.disk'));

            $post->media->each(function ($item, $key) use ($disk) {
                if ($item->delete()) {
                    foreach ($item->variations as $variation) {
                        $disk->delete($variation['path']);
                    }
                }
            });

            return response("Done", 202);

        }

        abort(403, "No permission");
    }

}