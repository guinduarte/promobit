<?php

namespace App\Repository;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    protected $passwordHasherInterface;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasherInterface)
    {
        $this->passwordHasherInterface = $passwordHasherInterface;

        parent::__construct($registry, User::class);
    }

    public function findById(string $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleted_at is null')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleted_at is null')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function list()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleted_at is null')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function create(array $data)
    {
        $now = new DateTimeImmutable;
        $user = new User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasherInterface->hashPassword($user, $data['password']));
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function update(User $user, array $data)
    {
        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['reset_password'])) {
            $user->setResetPassword($data['reset_password']);
        }

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        $user->setUpdatedAt((new DateTimeImmutable));

        if (isset($data['password'])) {
            $user->setPassword($this->passwordHasherInterface->hashPassword($user, $data['password']));
        }

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function delete(User $user)
    {
        $user->setDeletedAt((new DateTimeImmutable()));

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function restore(User $user)
    {
        $user->setDeletedAt(null);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }
}
