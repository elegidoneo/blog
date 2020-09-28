<?php


namespace App\Repositories;

use App\Http\Requests\UserStoreRequest;

class UserStoreRepository extends UserRepository
{
    /**
     * Repository constructor.
     * @param UserStoreRequest $request
     * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
     */
    public function __construct(UserStoreRequest $request)
    {
        parent::__construct($request);
    }
}
