<?php

namespace Gurinder\LaravelBlog\Http\Controllers;

use Illuminate\Http\Request;
use Gurinder\LaravelBlog\Models\Post;
use Gurinder\LaravelBlog\Repositories\PostRepository;
use Gurinder\LaravelBlog\Repositories\MediaRepository;

class PostsContentImagesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param         $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
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

        if (starts_with($request->file->getMimeType(), 'image/')) {
            return $this->storeImage($post, $request->file);
        }

        if (starts_with($request->file->getMimeType(), 'video/')) {
            return $this->storeVideo($post, $request->file);
        }

        return response("Error", 422);
    }

    /**
     * @param              $post
     * @param UploadedFile $image
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function storeImage($post, UploadedFile $image)
    {

        if ($media = (new PostRepository($post))->uploadImageAndCreateMedia($image, [], true)) {
            return response()->json([
                'url'   => storageUrl($media->variations['original']['path']),
                'media' => $media
            ], 200);
        }

        return response("Error", 500);
    }

    /**
     * @param              $note
     * @param UploadedFile $video
     */
    protected function storeVideo($note, UploadedFile $video)
    {

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
        return response("Error", 500);
    }
}