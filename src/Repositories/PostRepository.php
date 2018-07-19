<?php

namespace Gurinder\LaravelBlog\Repositories;


use Gurinder\LaravelBlog\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Gurinder\Storage\Facades\Storage as GurinderStorage;

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
    public function saveFeaturedImageOfThePost($image, $public = false)
    {
        $variations = config('media.image_variations');

        $imageData = GurinderStorage::setPublic($public)->uploadImage($image);

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
    public function updateFeaturedImageOfThePost($image, $public = false)
    {
        $variations = config('media.image_variations');

        $disk = $this->post->featuredImage->storage_disk;

        if ($this->post->featuredImage) {

            GurinderStorage::removeImages(
                $disk,
                collect($this->post->featuredImage->variations)->pluck('path')->all(),
                $disk == 'local' ? $this->post->featuredImage->public : false
            );

        }

        $imageData = GurinderStorage::setPublic($public)->uploadImage($image);

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
    public function uploadImageAndCreateMedia($image, $variations = null, $public = false)
    {

        if(is_null($variations)) {
            $variations = config('media.image_variations');
        }

        $imageData = GurinderStorage::setVariations($variations)->setPublic($public)->uploadImage($image);

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
    protected function uploadImage($image, $public = false)
    {
        return GurinderStorage::uploadImage($image, $public);
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
        return config('filesystems.default');
    }

}