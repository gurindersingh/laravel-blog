<?php

namespace Gurinder\LaravelBlog\Rules;

use Gurinder\LaravelBlog\Models\Post;
use Illuminate\Contracts\Validation\Rule;

class ValidPostSlug implements Rule
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * Create a new rule instance.
     *
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
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
        if (empty($this->slug)) return false;

        return !Post::where('slug', $this->slug)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Choose different title, its slug have been used already';
    }
}
