<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Gurinder\LaravelBlog\Repositories\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Models\Media;
use Gurinder\LaravelBlog\Repositories\ImageManager;
use Gurinder\LaravelBlog\Repositories\MediaRepository;

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
            if ($media = (new PostRepository($post))->uploadImageAndCreateMedia($request->file, [], true)) {
                return response()->json([
                    'files' => [
                        [
                            'url'   => storageUrl($media->variations['original']['path']),
                            'media' => $media
                        ]
                    ]
                ], 200);
            }

            return response("Error", 500);
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

        if ((new MediaRepository())->deleteByVariationPath($request->file)) {
            return response("Media deleted", 202);
        }

        // $path = str_replace(config('media.cloud_url_prefix'), '', $request->file);
        //
        // $media = Media::where('variations->original->path', ltrim($path, '/'))->firstOrFail();
        //
        // $paths = collect($media->variations)->pluck('path')->all();
        //
        // if ($media->delete()) {
        //
        //     (new ImageManager)->remove($media->storage_disk, $paths);
        //
        //     return response("Media deleted", 202);
        // }

        return response("Error", 500);
    }

    // /**
    //  * @param string | UploadedFile $file
    //  * @return array
    //  */
    // protected function uploadImageAndCreateMedia($file)
    // {
    //     $data = (new ImageManager())->upload(config('media.disk'), $file, 'images', [], true);
    //
    //     return (new MediaRepository())->create($data);
    // }

}