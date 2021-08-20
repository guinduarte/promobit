<?php

namespace App\Service;

use App\Entity\User;
use App\Helper\RandomString;
use App\Helper\Uuid;
use App\Notification\RequestPasswordNotification;
use App\Notification\ResetPasswordNotification;
use App\Repository\TokenRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Security\TokenGenerator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class AuthService
{
    protected $repository, $tokenRepository, $tokenGenerator, $queue, $mailer;

    function __construct(TokenRepositoryInterface $tokenRepository, UserRepositoryInterface $repository, TokenGenerator $tokenGenerator, MessageBusInterface $queue, MailerInterface $mailer)
    {
        $this->repository = $repository;

        $this->tokenRepository = $tokenRepository;

        $this->tokenGenerator = $tokenGenerator;

        $this->queue = $queue;

        $this->mailer = $mailer;
    }

    public function login(string $email): ?User
    {
        $user = $this->repository->findByEmail($email);
        $hash = Uuid::generate();

        $this->tokenRepository->create($user->getEmail(), $hash);

        $user->token = $this->tokenGenerator->generate([
            '_id' => $hash
        ]);

        return $user;
    }

    public function getToken($hash)
    {
        return $this->tokenRepository->getByHash($hash);
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