<?php

namespace Gurinder\LaravelBlog\Models;


use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $table = 'tags';

    public $timestamps = false;

    protected $hidden = ['pivot'];

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

}