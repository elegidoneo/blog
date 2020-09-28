<?php


namespace App\Repositories;

use App\Http\Requests\UserStoreRequest;

class UserStoreRepository extends UserRepository
{
    /**
     * Repository constructor.
     * @param UserStoreRequest|null $request
     */
    public function __construct(UserStoreRequest $request = null)
    {
        parent::__construct($request);
    }
}
