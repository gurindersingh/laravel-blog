<?php

namespace Gurinder\LaravelBlog\Http\Requests;


use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Media;
use Gurinder\LaravelBlog\Repositories\Images\ImageManager;
use Illuminate\Foundation\Http\FormRequest;
use Gurinder\LaravelBlog\Rules\ValidPostSlug;
use Gurinder\LaravelBlog\Rules\ValidCategoryId;
use Gurinder\Storage\Facades\Storage as GStorage;
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
                'featured_image_id'  => 'required|exists:media,id',
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


        if ($media = $this->uploadFeaturedImage($post)) {
            $post->media()->save($media);
            $post->update(['featured_image_id' => $media->id]);
        }

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
            $this->updateFeaturedImage($post);
            // if ($media = $this->updateFeaturedImage($post)) {
            //     $post->media()->save($media);
            //     $data = $data + ['featured_image_id' => $media->id];
            // }
        }

        $this->syncTags($post);

        $post->update($data);

        return $post->fresh(['featuredImage', 'category', 'tags']);
    }

    /**
     * @param Post $post
     * @return bool
     * @return Media
     */
    protected function uploadFeaturedImage(Post $post)
    {
        $variations = config('media.image_variations');

        $imageData = GStorage::uploadImage(config('media.disk'), $this->featured_image_raw, '/images', $variations, true);

        return $this->createMedia($imageData);
    }

    /**
     * @param Post $post
     * @return void
     */
    protected function updateFeaturedImage(Post $post)
    {
        if ($post->featuredImage) {
            (new ImageManager)->remove(
                $post->featuredImage->storage_disk,
                collect($post->featuredImage->variations)->pluck('path')->toArray()
            );
        }

        $imageData = (new ImageManager)->upload(
            config('media.disk'),
            $this->featured_image_raw,
            '/images',
            config('media.image_variations'),
            true
        );

        $post->featuredImage->update([
            'name'        => $imageData['name'],
            'extension'   => $imageData['extension'],
            'mime_type'   => $imageData['mime_type'],
            'file_type'   => $imageData['file_type'],
            'public'      => $imageData['public'],
            'variations'  => $imageData['variations'],
            'uploaded_by' => auth()->id()
        ]);

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

    protected function createMedia($data)
    {
        return Media::create([
            'name'         => $data['name'],
            'extension'    => $data['extension'],
            'mime_type'    => $data['mime_type'],
            'file_type'    => $data['file_type'],
            'public'       => $data['public'],
            'variations'   => $data['variations'],
            'properties'   => [
                'alt_text'    => null,
                'caption'     => null,
                'description' => null,
            ],
            'storage_disk' => $data['storage_disk'],
            'uploaded_by'  => auth()->id(),
        ]);
    }
}