<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    protected $repository;

    function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listUsers()
    {
        return $this->repository->list();
    }

    public function createUser(array $data): User
    {
        $duplicatedEmail = $this->repository->findByEmail($data['email']);

        if ($duplicatedEmail) {
            throw new HttpException(422, json_encode(['errors' => 'E-mail has already been taken']));
        }

        return $this->repository->create($data);
    }

    public function updateUser(string $id, array $data): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new HttpException(422, json_encode(['errors' => 'User not found.']));
        }

        $duplicatedEmail = $this->repository->findByEmail($data['email']);

        if ($duplicatedEmail && $duplicatedEmail->getId() != $id) {
            throw new HttpException(422, json_encode(['errors' => 'E-mail has already been taken']));
        }

        return $this->repository->update($user, $data);
    }

    public function deleteUser(string $id): User
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            throw new HttpException(422, json_encode(['errors' => 'User not found.']));
        }

        return $this->repository->delete($user);
    }
}