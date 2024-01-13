<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = new User();

        $user->setEmail('admin@info.com');

        $password = $this->hasher->hashPassword($user,'admin_123');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
