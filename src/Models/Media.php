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

    protected $appends = [
        'thumbnail_url'
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
    public function getFullNameAttribute()
    {
        return "{$this->name}.{$this->extension}";
    }

    public function getThumbnailUrlAttribute()
    {

        $disk = $this->storage_disk;

        if ($path = optional($this->featuredImage)['variations']['thumbnail']['path']) {

            if ($cloudUrlPrefix = config("media.cloud_url.{$disk}")) {
                $pathPrefix = $prefix ?: trim($cloudUrlPrefix, '/');
                $path = trim($path, '/');
                return "{$pathPrefix}/{$path}";
            }

        }

        return null;
    }

}
