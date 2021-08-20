<?php

namespace App\Repository;

use App\Document\Token;
use DateTimeImmutable;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class TokenRepository extends ServiceDocumentRepository implements TokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Token::class);
    }

    public function create(string $user, string $hash): Token
    {
        $token = new Token();
        $token->setUser($user);
        $token->setHash($hash);
        $token->setCreatedAt(new DateTimeImmutable);

        $this->getDocumentManager()->persist($token);
        $this->getDocumentManager()->flush();

        return $token;
    }

    public function getByHash(string $hash): ?string
    {
        $token = $this->createQueryBuilder('t')
            ->field('hash')->equals($hash)
            ->getQuery()
            ->getSingleResult();

        return $token ? $token->getUser() : null;
    }
}
