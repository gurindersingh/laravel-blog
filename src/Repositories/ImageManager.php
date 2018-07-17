<?php

namespace Gurinder\LaravelBlog\Repositories;


use Illuminate\Http\UploadedFile;
use Gurinder\Storage\Facades\Storage;

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
    public function upload($disk = 'local', $image, $variations = [], $public = false, $path = 'images')
    {
        return Storage::uploadImage($disk, $image, $variations, $public, $path);
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