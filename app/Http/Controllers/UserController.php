<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserStoreRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $repository
     * @return UserCollection
     */
    public function index(UserRepository $repository)
    {
        return new UserCollection($repository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRepository $repository
     * @return UserResource
     */
    public function store(UserStoreRepository $repository)
    {
        return new UserResource($repository->create());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param \App\Models\User $user
     * @param UserRepository $userRepository
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, User $user, UserRepository $userRepository)
    {
        return $request->has("password") ?
            null : new UserResource($userRepository->update($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @param UserRepository $userRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user, UserRepository $userRepository)
    {
        $model = $userRepository->destroy($user);
        return response()->json([
            "message" => __("user.delete", ["name" => $model["name"]]),
        ]);
    }
}
