<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;

    public function findByEmail(string $email): ?User;

    public function list();

    public function create(array $data);

    public function update(User $user, array $data);

    public function delete(User $user);

    public function restore(User $user);
}
