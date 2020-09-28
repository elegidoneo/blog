<?php


namespace App\Repositories;

use App\Http\Requests\CommentUpdateRequest;

class CommentUpdateRepository extends CommentRepository
{
    /**
     * CommentUpdateRepository constructor.
     * @param CommentUpdateRequest $request
     * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
     */
    public function __construct(CommentUpdateRequest $request)
    {
        parent::__construct($request);
    }
}
