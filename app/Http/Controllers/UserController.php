<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserStoreRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param UserRepository $repository
     * @return UserCollection|JsonResponse
     * @throws \Throwable
     */
    public function index(UserRepository $repository)
    {
        try {
            throw_unless(
                app(Request::class)->user()->isAdmin(),
                new UnauthorizedException(__("auth.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            return new UserCollection($repository->all());
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
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
     * @return UserResource|JsonResponse
     */
    public function show(User $user)
    {
        try {
            throw_unless(
                app(Request::class)->user()->can('view', $user),
                new UnauthorizedException(__("auth.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            return new UserResource($user);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param \App\Models\User $user
     * @param UserRepository $userRepository
     * @return UserResource|JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user, UserRepository $userRepository)
    {
        try {
            throw_unless(
                app(Request::class)->user()->can('update', $user),
                new UnauthorizedException(__("auth.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            return $request->has("password") ?
                null : new UserResource($userRepository->update($user));
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
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
        try {
            throw_unless(
                app(Request::class)->user()->can('delete', $user),
                new UnauthorizedException(__("auth.unauthorized"), JsonResponse::HTTP_UNAUTHORIZED)
            );
            $model = $userRepository->destroy($user);
            return response()->json([
                "message" => __("user.delete", ["name" => $model["name"]]),
            ]);
        } catch (\Throwable $exception) {
            logger()->error(__METHOD__, compact('exception'));
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }
    }
}
