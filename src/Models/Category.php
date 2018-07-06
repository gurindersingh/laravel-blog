<?php

namespace Gurinder\LaravelBlog\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'categories';

    public $timestamps = false;

    protected $hidden = ['pivot'];

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

}