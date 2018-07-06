<?php

namespace Gurinder\LaravelBlog\Rules;

use Gurinder\LaravelBlog\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class ValidCategoryId implements Rule
{
    /**
     * @var string
     */
    protected $postType;

    /**
     * Create a new rule instance.
     *
     * @param string $postType
     */
    public function __construct(string $postType)
    {
        $this->postType = $postType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->postType == 'page') return true;

        return Category::where('id', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Category is not valid';
    }
}
