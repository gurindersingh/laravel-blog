<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Gurinder\LaravelBlog\Models\Post;
use Illuminate\Http\Request;
use TomLingham\Searchy\Facades\Searchy;

class PagesController extends Controller
{

    public function index(Request $request)
    {
        $searchingFor = $request->search ? "Showing results for <strong>{$request->search}</strong>" : null;

        $pages = Post::page();

        if ($request->search) {
            $pages = Post::where('id', 'like', "%{$request->search}%")->orWhere('title', 'like', "%{$request->search}%")->page();
        }

        $pages = $pages->with(['featuredImage', 'author'])->paginate($request->perPage ?: 20);

        return view('gblog::pages.index', compact('pages', 'searchingFor'));
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