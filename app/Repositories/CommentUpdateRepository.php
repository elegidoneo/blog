<?php


namespace App\Repositories;


use App\Http\Requests\CommentUpdateRequest;

class CommentUpdateRepository extends CommentRepository
{
    /**
     * CommentUpdateRepository constructor.
     * @param CommentUpdateRequest|null $request
     */
    public function __construct(CommentUpdateRequest $request = null)
    {
        parent::__construct($request);
    }
}
