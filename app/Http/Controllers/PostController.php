<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Lib\Uploads\PostImageUpload;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param PostRepository $repository
     * @return PostCollection
     */
    public function index(PostRepository $repository)
    {
        return new PostCollection($repository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request
     * @param PostImageUpload $postImageUpload
     * @param PostRepository $postRepository
     * @return PostResource|\Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(CreatePostRequest $request, PostImageUpload $postImageUpload, PostRepository $postRepository)
    {
        try {
            $fileName = $postImageUpload->upload();
            throw_unless($fileName, new UploadException(__("post.file.error")));
            $request->merge(["image_url" => $fileName]);
            return new PostResource($postRepository->create());
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json([
                "error" => $exception->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     * @param PostImageUpload $postImageUpload
     * @param PostRepository $repository
     * @return PostResource|JsonResponse
     */
    public function update(
        UpdatePostRequest $request,
        Post $post,
        PostImageUpload $postImageUpload,
        PostRepository $repository
    ) {
        try {
            throw_unless(
                $request->user()->can('update', $post),
                new \Exception(__("post.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            $fileName = $postImageUpload->upload();
            throw_unless(
                $fileName,
                new UploadException(__("post.file.error"), JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
            );
            if ($post->image_url !== $fileName) {
                $postImageUpload->deleteFile($post->image_url);
            }
            $request->merge(["image_url" => $fileName]);
            $repository->update($post);
            return new PostResource($post);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @param PostRepository $repository
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        try {
            throw_unless(
                app(Request::class)->user()->can('delete', $post),
                new \Exception(__("post.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            $repository->destroy($post);
            return response()->json([
                "message" => __("post.delete"),
            ]);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
    }
}
