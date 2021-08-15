<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RequestPassword;
use App\Http\Requests\V1\ResetPassword;
use App\Http\Resources\V1\UserResource;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends ApiController
{
    protected $service;

    function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->service->login($request->get('email'));

        return new JsonResponse(['data' => $user]);

        // return new UserResource($login);
    }

    public function requestPassword(RequestPassword $request): JsonResponse
    {
        $this->service->requestPassword($request->get('email'));

        return new JsonResponse(['message' => 'Reset code has been send.']);
    }

    public function resetPassword(ResetPassword $request): JsonResponse
    {
        $this->service->resetPassword($request->get('email'), $request->get('code'), $request->get('password'));

        return new JsonResponse(['message' => 'Your password has been changed!']);
    }
}