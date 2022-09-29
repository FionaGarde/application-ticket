<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $faker = Factory::create('FR,fr');
            $user = new User();
            $user->setEmail($faker->firstName().".".$faker->lastName().'@my-digital-school.org');
            $user->setPassword('userpass');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
