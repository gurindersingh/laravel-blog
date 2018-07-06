<?php

namespace Gurinder\LaravelBlog\Http\Controllers;


use Gurinder\LaravelBlog\Models\Category;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Tag;

class BlogProfileController extends Controller
{

    public function __invoke()
    {
        $pagesCount = Post::page()->count();

        $postsCount = Post::post()->count();

        $categoriesCount = Category::count();

        $tagsCount = Tag::count();

        return view('gblog::blog.index', compact('pagesCount', 'postsCount', 'categoriesCount', 'tagsCount'));

    }

}