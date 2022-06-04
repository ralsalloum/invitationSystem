<?php

namespace App\DataFixtures;

use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         for ($i = 0; $i < 2; $i++) {
             $userEntity = new UserEntity('user'.$i.'@example.com');

             $userEntity->setPassword("123456");
             $userEntity->setRoles(['ROLE_USER']);

             $manager->persist($userEntity);
         }

        $manager->flush();
    }
}
