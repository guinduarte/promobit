<?php

namespace App\Service;

use App\Entity\User;
use App\Helper\RandomString;
use App\Helper\Uuid;
use App\Notification\RequestPasswordNotification;
use App\Notification\ResetPasswordNotification;
use App\Repository\UserRepositoryInterface;
use App\Security\TokenGenerator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class AuthService
{
    protected $repository, $tokenGenerator, $queue, $mailer;

    function __construct(UserRepositoryInterface $repository, TokenGenerator $tokenGenerator, MessageBusInterface $queue, MailerInterface $mailer)
    {
        $this->repository = $repository;

        $this->tokenGenerator = $tokenGenerator;

        $this->queue = $queue;

        $this->mailer = $mailer;
    }

    public function login(string $email): ?User
    {
        $user = $this->repository->findByEmail($email);

        // token info
        $token = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            // 'created_at' => '<created_at>',
            // 'last_active_at' => '<last_active_at>',
            // 'ip' => '<user_ip>',
            // 'device_id' => '<device_id>',
            // 'fcm_id' => '<fcm_id>',
        ];

        $uuid = Uuid::generate();

        // storage $token with $uuid identifier key
        // $this->tokenRepository->create($uuid, $token);

        $user->token = $this->tokenGenerator->generate([
            '_id' => $uuid,
            'email' => $user->getEmail()
        ]);

        return $user;
    }

    public function requestPassword(string $email): bool
    {
        $user = $this->repository->findByEmail($email);

        if (!$user) {
            throw new HttpException(422, json_encode(['errors' => 'User not found.']));
        }

        $code = RandomString::generate(6);

        $this->repository->update($user, [
            'reset_password' => $code
        ]);

        $this->queue->dispatch(new RequestPasswordNotification([
            'email' => $user->getEmail(),
            'code' => $code,
        ]));

        return true;
    }

    public function resetPassword(string $email, string $code, string $password): ?User
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || $user->getResetPassword() != $code) {
            throw new HttpException(422, json_encode(['errors' => 'Code invalid.']));
        }

        $this->repository->update($user, [
            'password' => $password,
            'reset_password' => ''
        ]);

        $this->queue->dispatch(new ResetPasswordNotification([
            'email' => $user->getEmail(),
            'name' => $user->getName(),
        ]));

        return $user;
    }
}