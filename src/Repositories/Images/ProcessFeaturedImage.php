<?php

namespace Gurinder\LaravelBlog\Repositories\Images;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProcessFeaturedImage
{

    public function upload(UploadedFile $file, $modelSlug, $variations = null)
    {
        $media = (new ImageUploader($file, $modelSlug, $variations))->upload();

        return $media;
    }

    /**
     * @param $string
     * @param $modelSlug
     * @param $modelId
     * @return bool
     */
    public function uploadBase64($string, $modelSlug, $modelId)
    {
        $imageParts = explode(";base64,", $string);

        $imageBase64Data = $imageParts[1];

        $extension = explode("image/", $imageParts[0])[1];

        if ($tempFilePath = $this->uploadImageToTemporaryFolder($modelSlug, $imageBase64Data, $extension, $modelId)) {

            $absolutePath = storage_path("app" . DIRECTORY_SEPARATOR . $tempFilePath);

            $uploadedFileInstance = getUploadedFileInstance($absolutePath);

            $media = (new ImageUploader($uploadedFileInstance, $modelSlug))->upload();

            $path = explode(DIRECTORY_SEPARATOR, $tempFilePath);

            array_pop($path);

            Storage::disk('local')->deleteDirectory(implode(DIRECTORY_SEPARATOR, $path));

            return $media;
        }

        return false;
    }

    /**
     * @param Model $previousFeaturedImage
     * @param       $newFeaturedImage
     * @param       $modelSlug
     * @param       $modelId
     * @return bool|null
     */
    public function updateBase64(Model $previousFeaturedImage, $newFeaturedImage, $modelSlug, $modelId)
    {
        try {

            if ($previousFeaturedImage) {
                (new RemoveImage())->remove($previousFeaturedImage);
            }

            return $this->uploadBase64($newFeaturedImage, $modelSlug, $modelId);

        } catch (\Exception $exception) {

            return false;

        }
    }

    /**
     * @param $name
     * @param $imageBase64Data
     * @param $extension
     * @param $modelId
     * @return null|string
     */
    protected function uploadImageToTemporaryFolder($name, $imageBase64Data, $extension, $modelId)
    {
        $imageDir = "temp" . DIRECTORY_SEPARATOR . md5("articles-" . time() . str_random(10) . $modelId);

        $tempFilePath = $imageDir . DIRECTORY_SEPARATOR . "$name.$extension";

        if (Storage::disk('local')->put($tempFilePath, base64_decode($imageBase64Data))) {
            return $tempFilePath;
        }

        return null;
    }
}