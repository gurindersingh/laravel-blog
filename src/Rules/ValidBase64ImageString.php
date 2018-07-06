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
    public function passes($attribute, $value)
    {
        return validateBase64ImageString($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please provide valid image type';
    }
}
