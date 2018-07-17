<?php

namespace Gurinder\LaravelBlog\Repositories\Images;

use Gurinder\LaravelBlog\Models\Media;
use Gurinder\LaravelBlog\Models\Post;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageUploaderOLD
{
    protected $disk;

    /**
     * @var UploadedFile
     */
    protected $uploadedImage;

    /**
     * @var null
     */
    protected $modelSlug;

    protected $interventionImage;

    protected $imageInContext;

    protected $name;

    protected $extension;

    protected $variations;

    protected $media = [];

    protected $onlyOriginal = false;

    protected $post;

    public function __construct(UploadedFile $uploadedImage = null, $modelSlug = null, $variations = null, $post = null)
    {
        $this->disk = Storage::disk(config('media.disk'));
        $this->uploadedImage = $uploadedImage;
        $this->modelSlug = $modelSlug;
        $this->name = $this->getName();
        $this->extension = $this->uploadedImage->getClientOriginalExtension();
        $this->variations = $variations ?? config('media.image_variations');
        $this->post = $post;
    }

    public function upload()
    {
        $this->setInterventionImage();

        $this->setMedia();

        if ($this->onlyOriginal) {
            $this->saveOriginal(); // onlyOriginal
        } else {
            $this->saveVariations();
        }


        return $this->createMedia();
    }

    public function saveOriginal($public = true)
    {
        $image = $this->interventionImage;

        $path = '/images/' . $this->name . '-' . $image->width() . 'x' . $image->height() . '.' . $this->extension;

        $this->disk->put($path, $image->stream($this->extension), $public ? 'public' : '');

        $this->media['variations']['original'] = [
            'path'      => $path,
            'url'       => $this->disk->url($path),
            'mime_type' => $this->uploadedImage->getClientMimeType(),
            'width'     => $image->width(),
            'height'    => $image->height()
        ];

        $this->imageInContext = null;
    }

    protected function saveVariations()
    {
        foreach ($this->variations as $variationType => $variation) {

            $this->interventionImage->backup();

            $image = $this->interventionImage->fit($variation['width'], $variation['height']);

            $path = '/images/' . $this->name . '-' . $variation['width'] . 'x' . $variation['height'] . '.' . $this->extension;

            $this->disk->put($path, $image->stream($this->extension), 'public');

            $this->media['variations'][$variationType] = [
                'path'      => $path,
                'url'       => $this->disk->url($path),
                'mime_type' => $this->uploadedImage->getClientMimeType(),
                'width'     => $variation['width'],
                'height'    => $variation['height']
            ];

            $this->interventionImage->reset();
        }

        return $this;
    }

    protected function createMedia()
    {
        if ($this->post) {
            return $this->post->media()->create($this->media);
        }
        return Media::create($this->media);
    }

    protected function setMedia()
    {
        $this->media = [
            'extension'    => $this->extension,
            'mime_type'    => $this->uploadedImage->getClientMimeType(),
            'file_type'    => 'image',
            'public'       => true,
            'variations'   => [],
            'properties'   => [
                'alt_text'    => null,
                'caption'     => null,
                'description' => null,
            ],
            'storage_disk' => config('media.disk'),
            'uploaded_by'  => auth()->id(),
        ];
    }

    protected function getName($name = null, $append = '')
    {
        if ($name) {
            return $name . $append;
        }
        return md5($this->modelSlug . time() . str_random(10));
    }

    /**
     * Set intervention instance to variable for use
     */
    public function setInterventionImage()
    {
        $this->interventionImage = Image::make($this->uploadedImage);;

        $orientation = $this->interventionImage->exif('Orientation');

        if (!empty($orientation)) {

            switch ($orientation) {
                case 8:
                    $this->interventionImage = $this->interventionImage->rotate(90);
                    break;

                case 3:
                    $this->interventionImage = $this->interventionImage->rotate(180);
                    break;

                case 6:
                    $this->interventionImage = $this->interventionImage->rotate(-90);
                    break;
            }

        }

        return $this;
    }

    /**
     * @param bool $onlyOriginal
     * @return ImageUploader
     */
    public function setOnlyOriginal(bool $onlyOriginal): ImageUploader
    {
        $this->onlyOriginal = $onlyOriginal;
        return $this;
    }
}