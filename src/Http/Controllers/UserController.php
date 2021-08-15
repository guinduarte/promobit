<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\UserCreateRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends ApiController
{
    protected $service;

    function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function list(): JsonResponse
    {
        $users = $this->service->listUsers();

        return new JsonResponse([
            'users' => (new UserResource($users))->getData()
        ]);
    }

    public function create(UserCreateRequest $request): JsonResponse
    {
        $user = $this->service->createUser($request->data());

        return new JsonResponse([
            'message' => 'User created',
            'user' => (new UserResource($user))->getData()
        ]);
    }

    public function update(string $id, UserUpdateRequest $request): JsonResponse
    {
        $user = $this->service->updateUser($id, $request->data());

        return new JsonResponse([
            'message' => 'User updated',
            'user' => (new UserResource($user))->getData()
        ]);
    }

    public function delete(string $id): JsonResponse
    {
        $user = $this->service->deleteUser($id);

        return new JsonResponse([
            'message' => 'User deleted',
            'user' => (new UserResource($user))->getData()
        ]);
    }
}