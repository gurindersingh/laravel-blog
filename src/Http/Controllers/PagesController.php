<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Gurinder\LaravelBlog\Models\Post;

class PagesController extends Controller
{

    public function index()
    {
        $pages = Post::page()->with(['featuredImage', 'author'])->paginate(20);

        return view('gblog::pages.index', compact('pages'));
    }

    public function create()
    {
        return view('gblog::pages.create');
    }


    public function edit($id)
    {
        $page = Post::page()->whereId($id)->with(['featuredImage', 'media'])->firstOrFail();

        return view('gblog::pages.edit', compact('page'));
    }
}