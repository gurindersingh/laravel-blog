<?php

namespace Gurinder\LaravelBlog\Repositories\Images;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RemoveImage
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk(config('media.disk'));
    }

    public function remove(Model $image)
    {
        try {
            $variations = $image->variations;

            foreach ($variations as $variation => $values) {
                $this->disk->delete($values['path']);
            }

            return $image->delete();
        } catch (\Exception $e) {
            return false;
        }
    }
}