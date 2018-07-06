<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Illuminate\Http\Request;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Media;
use Gurinder\LaravelBlog\Repositories\Images\ImageUploader;

class PostsContentImagesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $request->validate([
            'file' => [
                'required',
                'mimetypes:image/jpeg,image/jpg,image/png',
                'dimensions:max_width=2048',
                'max:9000'
            ]
        ]);

        $post = Post::whereId($id)->where('author_id', auth()->id())->firstOrFail();

        try {
            if ($media = $this->uploadImage($request, $post)) {
                return response()->json([
                    'files' => [
                        [
                            'url'   => $media->variations['original']['url'],
                            'media' => $media
                        ]
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response("Something went wrong", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate(['file' => 'required|string']);

        $media = Media::where('variations->original->url', $request->file)->firstOrFail();

        if ($media->delete()) {

            $disk = \Storage::disk(config('media.disk'));

            foreach ($media->variations as $variation) {

                $disk->delete($variation['path']);

            }

            return response("Media deleted", 202);
        }

        return response("Error", 500);
    }

    /**
     * @param Request $request
     * @param         $post
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function uploadImage(Request $request, $post): \Illuminate\Database\Eloquent\Model
    {
        return (new ImageUploader($request->file, $post->slug, null, $post))->setOnlyOriginal(true)->upload();
    }

}