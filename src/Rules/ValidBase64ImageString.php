<?php

namespace Gurinder\LaravelBlog\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidBase64ImageString implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $errorMessages = [];

    public function passes($attribute, $value)
    {
        try {

            $validMimeTypes = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];

            $base64string = base64_decode(explode(";base64,", $value)[1]);

            $info = getimagesizefromstring($base64string);

            unset($string);

            if (!$info || ($info[0] <= 0) || ($info[1] <= 0)) {
                $this->errorMessages[] = 'Invalid image info.';
            };

            if (is_array($validMimeTypes) && !empty($validMimeTypes)) {
                if (!in_array($info['mime'], $validMimeTypes)) {
                    $this->errorMessages[] = 'Invalid image mime type';
                }
            }

            $sizeInKB = (strlen($base64string) - substr_count(substr($base64string, -2), '=')) / 1024;

            if ($sizeInKB > 1500) {
                $this->errorMessages[] = "Image size should be smaller than 1500KB";
            }

            unset($base64string);

            return !!empty($this->errorMessages);

        } catch (\Exception $e) {

            return false;

        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return implode(" ", $this->errorMessages);
    }

}
