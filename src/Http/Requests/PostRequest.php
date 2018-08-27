<?php

namespace Gurinder\LaravelBlog\Http\Requests;


use Gurinder\LaravelBlog\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Gurinder\LaravelBlog\Rules\ValidPostSlug;
use Gurinder\LaravelBlog\Rules\ValidCategoryId;
use Gurinder\LaravelBlog\Repositories\PostRepository;
use Gurinder\LaravelBlog\Rules\ValidBase64ImageString;
use Gurinder\LaravelBlog\Rules\ValidAndUniquePostSlug;

class PostRequest extends FormRequest
{

    use RequestType;

    /**
     * @var
     */
    protected $newSlug;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        if ($this->requestToCreate()) {
            $this->newSlug = trim(str_slug($this->title, '-'));
            return [
                'featured_image_raw' => ['required', (new ValidBase64ImageString())],
                'title'              => ['required', 'string', new ValidPostSlug($this->newSlug)],
                'excerpt'            => 'required',
                'post_type'          => 'required|in:post,page',
                'category_id'        => new ValidCategoryId($this->post_type),
                'tags'               => 'nullable|array',
                'tags.*.id'          => 'exists:tags,id',
                'published_at'       => 'nullable|date_format:"Y-m-d H:i:s"',
                'table_of_content'   => 'nullable',
            ];
        }

        if ($this->requestToUpdate()) {

            $this->newSlug = trim(str_slug($this->slug, '-'));

            $ignoreId = $this->route()->parameter('post');

            if (!$ignoreId) abort(500);

            return [
                'featured_image_raw' => ['nullable', (new ValidBase64ImageString())],
                'featured_image_id'  => 'nullable|required_without:featured_image_raw|exists:media,id',
                'title'              => 'required|string',
                'excerpt'            => 'required',
                'post_type'          => 'required|in:post,page',
                'category_id'        => new ValidCategoryId($this->post_type),
                'tags'               => 'nullable|array',
                'tags.*.id'          => 'exists:tags,id',
                'publish'            => 'nullable',
                'published_at'       => 'nullable|date_format:"Y-m-d H:i:s"',
                'table_of_content'   => 'nullable',
                'slug'               => ['required', new ValidAndUniquePostSlug($ignoreId, $this->newSlug)]
            ];
        }

        if ($this->requestToDelete()) {
            return [];
        }
    }

    /**
     * @return mixed
     */
    public function persist()
    {

        dd("sdkvs");

        $post = Post::create([
            'title'                 => $this->title,
            'slug'                  => $this->newSlug,
            'category_id'           => $this->post_type == 'post' ? $this->category_id : null,
            'excerpt'               => $this->excerpt,
            'body'                  => $this->body,
            'post_type'             => $this->post_type,
            'author_id'             => $this->user()->id,
            'published_at'          => $this->published_at,
            'estimate_reading_time' => estimateReadingTime($this->content),
            'table_of_content'      => $this->table_of_content,
            'meta'                  => [
                'robots'   => optional($this->meta)['robots'] ?? 'all',
                'keywords' => $this->meta['keywords']
            ]
        ]);

        (new PostRepository($post))->saveFeaturedImageOfThePost(
            $this->featured_image_raw,
            true
        );

        $this->syncTags($post);

        return $post->fresh(['featuredImage', 'category', 'tags']);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function update($id)
    {

        $post = Post::whereId($id)->where('author_id', $this->user()->id)->with(['featuredImage'])->firstOrFail();

        $data = [
            'title'                 => $this->title,
            'slug'                  => $this->newSlug != $post->slug ? $this->newSlug : $post->slug,
            'excerpt'               => $this->excerpt,
            'category_id'           => $post->post_type == 'post' ? $this->category_id : null,
            'body'                  => $this->get('body'),
            'estimate_reading_time' => estimateReadingTime($this->body),
            'published_at'          => $this->published_at,
            'table_of_content'      => $this->table_of_content,
            'meta'                  => [
                'robots'   => optional($this->meta)['robots'] ?? 'all',
                'keywords' => $this->meta['keywords']
            ]
        ];

        if (is_string($this->featured_image_raw) && !empty(trim($this->featured_image_raw))) {

            if ($post->featuredImage) {
                (new PostRepository($post))->updateFeaturedImageOfThePost($this->featured_image_raw, true);
            } else {
                (new PostRepository($post))->saveFeaturedImageOfThePost($this->featured_image_raw, true);
            }


        }

        $this->syncTags($post);

        $post->update($data);

        return $post->fresh(['featuredImage', 'category', 'tags']);
    }


    /**
     * @param $post
     */
    protected function syncTags($post): void
    {
        if ($post->post_type == 'post') {

            $tagIds = collect($this->tags)->pluck('id')->toArray();

            $post->tags()->sync($tagIds);

        }
    }
}