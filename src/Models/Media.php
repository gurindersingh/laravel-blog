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
        'properties' => 'array',
        'public'     => 'boolean'
    ];

    protected $appends = [
        'image_original_url',
        'image_thumbnail_url',
        'image_social_url',
        'image_hd_url',
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

    public function getImageOriginalUrlAttribute()
    {
        return $this->getVariationImagePath('original.path');
    }

    public function getImageThumbnailUrlAttribute()
    {
        return $this->getVariationImagePath('thumbnail.path');
    }

    public function getImageHdUrlAttribute()
    {
        return $this->getVariationImagePath('hd.path');
    }

    public function getImageSocialUrlAttribute()
    {
        return $this->getVariationImagePath('social.path');
    }

    protected function getVariationImagePath($variation = '')
    {
        $disk = $this->storage_disk;

        if ($path = $path = data_get($this->variations, $variation)) {
            $prefix = null;
            if ($cloudUrlPrefix = config("media.cloud_url.{$disk}")) {
                $pathPrefix = $prefix ?: trim($cloudUrlPrefix, '/');
                $path = trim($path, '/');
                return "{$pathPrefix}/{$path}";
            }
        }
        return null;
    }

}
