<?php

namespace Gurinder\LaravelBlog\Http\Requests;


use Illuminate\Validation\Rule;
use Gurinder\LaravelBlog\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Gurinder\LaravelBlog\Repositories\Images\ProcessFeaturedImage;

class PageRequest extends FormRequest
{

    use RequestType;

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

    public function rules()
    {
        if ($this->requestToCreate()) {
            return [
                'featured_image'   => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (is_string($value) && !validateBase64ImageString($value)) {
                            return $fail('Featured Image is not valid');
                        }
                        return $value;
                    }
                ],
                'title'            => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if (Post::whereSlug(str_slug($value, '-'))->exists()) {
                            return $fail('Choose different title, its already been taken');
                        }
                        return $value;
                    },
                ],
                'excerpt'          => 'required',
                'published_at'     => 'nullable|date_format:"Y-m-d H:i:s"',
                'table_of_content' => 'nullable',
            ];
        }

        if ($this->requestToUpdate()) {

            $this->newSlug = str_slug($this->slug, '-');

            $ignoreId = $this->route()->parameter('page');

            if (!$ignoreId) abort(500);

            return [
                'featured_image'   => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (is_string($value) && !validateBase64ImageString($value)) {
                            return $fail('Featured Image is not valid');
                        }

                        return $value;
                    },
                ],
                'title'            => 'required|string',
                'excerpt'          => 'required',
                'publish'          => 'nullable',
                'published_at'     => 'nullable|date_format:"Y-m-d H:i:s"',
                'table_of_content' => 'nullable',
                'slug'             => [
                    'required',
                    Rule::unique('posts')
                        ->where(function ($q) {
                            $q->where('slug', $this->newSlug);
                        })
                        ->ignore($ignoreId, 'id')
                ]
            ];
        }

        if ($this->requestToDelete()) {
            return [];
        }
    }

    public function persist()
    {
        $page = Post::create([
            'title'                 => $this->title,
            'slug'                  => str_slug($this->title, '-'),
            'excerpt'               => $this->excerpt,
            'body'                  => $this->body,
            'post_type'             => 'page',
            'author_id'             => $this->user()->id,
            'published_at'          => $this->published_at,
            'estimate_reading_time' => estimateReadingTime($this->content),
            'table_of_content'      => $this->table_of_content,
            'meta'                  => [
                'robots'   => optional($this->meta)['robots'] ?? 'all',
                'keywords' => $this->meta['keywords']
            ]
        ]);


        if ($media = $this->uploadFeaturedImage($page)) {
            $page->update(['featured_image_id' => $media->id]);
        }

        return $page->fresh(['featuredImage']);

    }

    public function update($id)
    {
        $page = Post::whereId($id)->where('author_id', $this->user()->id)->with(['featuredImage'])->firstOrFail();

        $data = [
            'title'                 => $this->title,
            'excerpt'               => $this->excerpt,
            'slug'                  => $this->newSlug != $page->slug ? $this->newSlug : $apge->slug,
            'body'                  => $this->get('body'),
            'estimate_reading_time' => estimateReadingTime($this->body),
            'published_at'          => $this->published_at,
            'table_of_content'      => $this->table_of_content,
            'metas'                 => [
                'robots' => optional($this->metas)['robots'] ?? 'all',
                'keywords' => $this->meta['keywords']
            ]
        ];

        if (is_string($this->featured_image) && !empty(trim($this->featured_image))) {
            if ($media = $this->updateFeaturedImage($page)) {
                $data = $data + ['featured_image_id' => $media->id];
            }
        }

        $page->update($data);

        return $page->fresh(['featuredImage']);
    }

    /**
     * @param $page
     * @return Media|bool
     */
    protected function uploadFeaturedImage($page)
    {
        return (new ProcessFeaturedImage())->uploadBase64($this->featured_image, $page->slug, $page->id);
    }

    /**
     * @param $page
     * @return bool|Media
     */
    protected function updateFeaturedImage($page)
    {
        return (new ProcessFeaturedImage())
            ->updateBase64(
                $page->featuredImage,
                $this->featured_image,
                $page->slug,
                $page->id
            );
    }

}