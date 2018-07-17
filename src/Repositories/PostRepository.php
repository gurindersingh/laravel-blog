<?php

namespace Gurinder\LaravelBlog\Repositories;


use Gurinder\LaravelBlog\Models\Media;
use Illuminate\Database\Eloquent\Model;

class PostRepository
{
    /**
     * @var Model
     */
    protected $post;

    /**
     * @var
     */
    protected $disk;

    /**
     * PostRepository constructor.
     * @param Model|null $post
     */
    public function __construct(Model $post = null)
    {
        $this->post = $post;
    }

    /**
     * @param        $image
     * @param string $path
     * @param array  $variations
     * @param        $public
     * @return bool
     */
    public function saveFeaturedImageOfThePost($image, $variations = [], $public, $path = 'images')
    {
        $imageData = $this->uploadImage($image, $variations, $public, $path);

        $media = (new MediaRepository())->create($imageData);

        $this->post->media()->save($media);

        return $this->post->update(['featured_image_id' => $media->id]);
    }

    /**
     * @param       $image
     * @param       $path
     * @param array $variations
     * @param bool  $public
     * @return bool
     */
    public function updateFeaturedImageOfThePost($image, $variations = [], $public = false, $path = 'images')
    {
        if ($this->post->featuredImage) {
            (new ImageManager)->remove(
                $this->post->featuredImage->storage_disk,
                collect($this->post->featuredImage->variations)->pluck('path')->all()
            );
        }

        $imageData = $this->uploadImage($image, $variations, $public, $path);

        (new MediaRepository)->update($this->post->featuredImage, $imageData);

        return true;
    }

    /**
     * @param        $image
     * @param array  $variations
     * @param        $public
     * @param string $path
     * @return Media
     */
    public function uploadImageAndCreateMedia($image, $variations = [], $public = false, $path = 'images')
    {
        $imageData = $this->uploadImage($image, $variations, $public, $path);

        $media = (new MediaRepository())->create($imageData);

        $this->post->media()->save($media);

        return $media;
    }

    /**
     * @param        $image
     * @param array  $variations
     * @param bool   $public
     * @param string $path
     * @return array
     */
    protected function uploadImage($image, $variations = [], $public = false, $path = 'images')
    {
        return (new ImageManager())->upload($this->getDisk(), $image, $variations, $public, $path);
    }

    /**
     * @param mixed $post
     * @return PostRepository
     */
    public function setPost(Model $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @param mixed $disk
     * @return PostRepository
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk()
    {
        $disk = $this->disk ? $this->disk : config('media.disk');

        $this->disk = $disk;

        return $disk;
    }

}