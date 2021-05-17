<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $users = array();
        $admins = array();

        $admins[] = 'ROLE_ADMIN';
        $users[] = 'ROLE_USER';

        $admin = new User();
        $user = new User();

        $admin->setEmail('admin@mail.ru');
        $admin->setPassword(password_hash('qwerty', PASSWORD_DEFAULT));
        $admin->setRoles($users);
        $admin->setBalance(rand(0, 1000) / 10);

        $user->setEmail('testuser@mail.ru');
        $user->setPassword(password_hash('qwerty', PASSWORD_DEFAULT));
        $user->setRoles($admins);
        $user->setBalance(rand(0, 1000) / 10);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}