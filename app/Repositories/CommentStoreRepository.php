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
     * @param CommentRequest $request
     * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
     */
    public function __construct(CommentRequest $request)
    {
        parent::__construct($request);
    }
}
