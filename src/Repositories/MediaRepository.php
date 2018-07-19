<?php

namespace Gurinder\LaravelBlog\Repositories;


use Gurinder\LaravelBlog\Models\Media;

class MediaRepository
{

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Media::create([
            'name'         => $data['name'],
            'extension'    => $data['extension'],
            'mime_type'    => $data['mime_type'],
            'file_type'    => $data['file_type'],
            'public'       => $data['public'],
            'variations'   => $data['variations'],
            'properties'   => [
                'alt_text'    => null,
                'caption'     => null,
                'description' => null,
            ],
            'storage_disk' => $data['storage_disk'],
            'uploaded_by'  => auth()->id(),
        ]);
    }

    /**
     * @param $media
     * @param $data
     * @return bool
     */
    public function update($media, $data)
    {
        if (is_int($media)) {
            $media = Media::whereId($media)->first();

            if (!$media) return false;
        }

        if (isset($data['properties'])) {
            $data = $data + ['properties' => $data['properties']];
        }

        return $media->update([
            'name'         => $data['name'],
            'extension'    => $data['extension'],
            'mime_type'    => $data['mime_type'],
            'file_type'    => $data['file_type'],
            'public'       => $data['public'],
            'variations'   => $data['variations'],
            'storage_disk' => $data['storage_disk'],
            'uploaded_by'  => auth()->id()
        ]);
    }

    public function deleteByVariationPath($variationPath)
    {
        $variationPath = str_replace(config('media.cloud_url_prefix'), '', $variationPath);

        $media = Media::where('variations->original->path', ltrim($variationPath, '/'))->firstOrFail();

        $paths = collect($media->variations)->pluck('path')->all();

        if ($media->delete()) {

            (new ImageManager)->remove($media->storage_disk, $paths);

            return true;
        }

        return false;
    }

}