<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Guilherme Duarte');
        $user->setEmail('guinasduarte@gmail.com');
        $user->setPassword('123456');

        $manager->persist($user);
        $manager->flush();
    }
}
