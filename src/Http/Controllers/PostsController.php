<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Gurinder\LaravelBlog\Models\Tag;
use Illuminate\Http\Request;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Category;
use Gurinder\LaravelBlog\Http\Requests\PostRequest;

class PostsController extends Controller
{

    public function index()
    {
        $posts = Post::with(['featuredImage', 'category', 'tags', 'author'])->paginate(20);

        return view('gblog::posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::whereParentId(null)->with('children')->get();

        $tags = Tag::get();

        return view('gblog::posts.create', compact('categories', 'tags'));
    }


    public function edit(Request $request, $id)
    {
        $post = Post::post()->whereId($id)->with(['featuredImage', 'category', 'tags'])->firstOrFail();

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