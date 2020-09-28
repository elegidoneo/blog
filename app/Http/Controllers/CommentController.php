<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Repositories\CommentRepository;
use App\Repositories\CommentStoreRepository;
use App\Repositories\CommentUpdateRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CommentRepository $repository
     * @return CommentCollection
     */
    public function index(CommentRepository $repository)
    {
        return new CommentCollection($repository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentStoreRepository $repository
     * @return CommentResource
     */
    public function store(CommentStoreRepository $repository)
    {
        return new CommentResource($repository->create());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentUpdateRepository $repository
     * @param Comment $comment
     * @return CommentResource
     */
    public function update(CommentUpdateRepository $repository, Comment $comment)
    {
        return new CommentResource($repository->update($comment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @param CommentRepository $repository
     * @return JsonResponse
     */
    public function destroy(Comment $comment, CommentRepository $repository)
    {
        try {
            throw_unless(
                app(Request::class)->user()->can('delete', $comment),
                new \Exception(__("comment.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            $repository->destroy($comment);
            return response()->json([
                "message" => __("comment.delete"),
            ]);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
    }
}
