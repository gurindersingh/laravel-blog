<?php

namespace Gurinder\LaravelBlog\Models;


use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use UrlShortner;

    protected $guarded = [];

    protected $table = 'posts';

    protected $casts = [
        'published_at' => 'datetime',
        'meta'         => 'json'
    ];

    protected $with = ['author'];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('post', function (Builder $builder) {
        //     $builder->where('post_type', 'post');
        // });
    }


    public function author()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'author_id', 'id')->select(['id', 'name']);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function featuredImage()
    {
        return $this->hasOne(Media::class, 'id', 'featured_image_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function scopePage($query)
    {
        return $query->where('post_type', 'page');
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->whereAuthorId($userId);
    }

    public function scopePost($query)
    {
        return $query->where('post_type', 'post');
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '!=', null);
    }

    public function isPublished()
    {
        return $this->published_at != null;
    }

    public function isDraft()
    {
        return $this->published_at == null;
    }

    public function editLink()
    {
        return route('admin.posts.edit', $this->id);
    }

    public function owns($user)
    {
        if (auth()->user()->can('manage-posts')) return true;

        return !!(auth()->check() && $this->author_id == $user->id);
    }

    public function path()
    {
        return url($this->slug);
    }

}