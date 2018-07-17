<?php

namespace Gurinder\LaravelBlog\Repositories\Images;


use Gurinder\Storage\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageManager
{

    /**
     * @param string                $disk
     * @param string | UploadedFile $image
     * @param string                $path
     * @param array                 $variations
     * @param bool                  $public
     * @return array
     */
    public function upload($disk = 'local', $image, $path = '/images', $variations = [], $public = false)
    {
        return Storage::uploadImage($disk, $image, $path, $variations, $public);
    }

    /**
     * @param string $disk
     * @param array  $variations
     * @return mixed
     */
    public function remove($disk = 'local', $variations = [])
    {
        return Storage::removeImages($disk, $variations);
    }

}