<?php


namespace App\Repositories;


use App\Http\Requests\CommentRequest;

/**
 * Class CommentStoreRepository
 * @package App\Repositories
 */
class CommentStoreRepository extends CommentRepository
{
    /**
     * CommentRepository constructor.
     * @param CommentRequest|null $request
     */
    public function __construct(CommentRequest $request = null)
    {
        parent::__construct($request);
    }
}
