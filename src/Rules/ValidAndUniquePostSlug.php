<?php

namespace Gurinder\LaravelBlog\Rules;

use Gurinder\LaravelBlog\Models\Post;
use Illuminate\Contracts\Validation\Rule;

class ValidAndUniquePostSlug implements Rule
{
    /**
     * @var int
     */
    protected $ignoreId;

    /**
     * @var string
     */
    protected $newSlug;

    /**
     * Create a new rule instance.
     *
     * @param int    $ignoreId
     * @param string $newSlug
     */
    public function __construct(int $ignoreId, string $newSlug)
    {
        $this->ignoreId = $ignoreId;
        $this->newSlug = $newSlug;
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
        return !Post::whereSlug($this->newSlug)->where('id', '!=', $this->ignoreId)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Choose different slug, it has been already used.';
    }
}
