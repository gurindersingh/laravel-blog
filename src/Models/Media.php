<?php

namespace Gurinder\LaravelBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

    /**
     * @var string
     */
    protected $table = 'media';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'variations' => 'array',
        'properties' => 'array'
    ];

    /**
     * Get all of the owning mediaable models.
     */
    public function mediaable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedBy()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'uploaded_by');
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->slug}.{$this->extension}";
    }

}
