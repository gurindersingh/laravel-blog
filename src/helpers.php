<?php

if (!function_exists('validateBase64ImageString')) {

    /**
     * Check if Image string is valid
     *
     * @param       $string
     * @param int   $maxSizeInMB
     * @param array $validMimeTypes
     * @return boolean
     */
    function validateBase64ImageString($string, $maxSizeInKB = 3, $validMimeTypes = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'])
    {

        $base64string = base64_decode(explode(";base64,", $string)[1]);

        $info = getimagesizefromstring($base64string);

        unset($string);

        if (!$info || ($info[0] <= 0) || ($info[1] <= 0)) return false;

        if (is_array($validMimeTypes) && !empty($validMimeTypes)) {
            if (!in_array($info['mime'], $validMimeTypes)) return false;
        }

        $sizeInKB = (strlen($base64string) - substr_count(substr($base64string, -2), '=')) / 1024;

        if ($sizeInKB > $maxSizeInKB) return false;

        unset($base64string);

        return false;

        // try {
        //
        //
        //
        // } catch (\Exception $exception) {
        //
        //     return false;
        //
        // }
    }
}

if (!function_exists('getUploadedFileInstance')) {

    /**
     * Get Uploaded File instance
     *
     * @param string $path
     * @param bool   $public
     * @return \Illuminate\Http\UploadedFile
     */
    function getUploadedFileInstance($path, $public = false)
    {
        $name = \Illuminate\Support\Facades\File::name($path);

        $extension = \Illuminate\Support\Facades\File::extension($path);

        $originalName = $name . '.' . $extension;

        $mimeType = \Illuminate\Support\Facades\File::mimeType($path);

        $size = \Illuminate\Support\Facades\File::size($path);

        $error = null;

        return new \Illuminate\Http\UploadedFile($path, $originalName, $mimeType, $size, $error, $public);
    }
}

if (!function_exists('estimateReadingTime')) {

    function estimateReadingTime($content, $average = 230)
    {
        $word = str_word_count(strip_tags($content));

        $m = floor($word / $average);

        if ($m > 60) {
            $h = floor($m / 60) . "h";
            $m = $h . floor($m / 60);
        }

        $s = floor($word % $average / ($average / 60));

        return "{$m}m.{$s}s";

    }
}

