<?php

namespace Gurinder\LaravelBlog\Http\Controllers;


use Gurinder\LaravelBlog\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    public function index(Request $request)
    {
        if ($query = $request->get('search')) {
            return Tag::where('label', 'LIKE', '%' . $query . '%')->select(['id', 'slug', 'label'])->get();
        }

        return Tag::get();
    }

    public function store(Request $request)
    {
        $slug = str_slug($request->label);

        $data = $request->validate([
            'label' => [
                'required',
                'unique:tags,label',
                function ($attribute, $value, $fail) use ($slug) {
                    if (Tag::whereSlug($slug)->exists()) {
                        return $fail("Choose Different label, its already been used");
                    }
                    return $value;
                }
            ]
        ]);

        return Tag::create([
            'label' => $data['label'],
            'slug'  => $slug
        ]);
    }
}