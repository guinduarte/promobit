<?php

namespace App\Repository;

use App\Document\Token;

interface TokenRepositoryInterface
{
    public function create(string $userId, string $hash): Token;
}
