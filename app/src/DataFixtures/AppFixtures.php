<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        #region Création des classes
        $bachelorTitles = [
            'Digital',
            'Webmarketing & Social Media',
            'Webdesign',
            'Création Numérique',
            'E-Commerce',
            'Développeur Web',
        ];

        $mbaTitles = [
            'Chief Digital Officer',
            'Expert Marketing Digital',
            'Expert UI/UX Design',
            'Direction Artistique Digitale',
            'Chargé d\'Affaires Web',
            'Big Data',
            'Développeur Full-Stack',
        ];

        for ($count = 1; $count <= 2; $count++) {
            $classroom = new Classroom();
            $classroom->setLevel('B' . $count);
            $classroom->setTitle('Cycle Web et Multimédia');

            $manager->persist($classroom);
        }

        foreach ($bachelorTitles as $title) {
            $classroom = new Classroom();
            $classroom->setLevel('B3');
            $classroom->setTitle($title);

            $manager->persist($classroom);
        }

        foreach ($mbaTitles as $title) {
            for ($count = 1; $count <= 2; $count++) {
                $classroom = new Classroom();
                $classroom->setLevel('M' . $count);
                $classroom->setTitle($title);

                $manager->persist($classroom);
            }
        }
        #endregion

        #region Création de 3 users et 1 admin
        $user = new User();
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setMail('admin@my-digital-school.org');
        $user->setCreatedAt(new \DateTime());
        $user->setRoles(['ROLE_ADMIN']);

        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($password);

        $manager->persist($user);

        for ($count = 1; $count <= 3; $count++) {
            $user = new User();
            $user->setFirstName('user' . $count);
            $user->setLastName($count . 'user');
            $user->setMail('user' . $count . '.' . $count . 'user@my-digital-school.org');
            $user->setCreatedAt(new \DateTime());
            $user->setRoles([]);

            $maxIdClassroom = count($manager->getRepository(Classroom::class)->findAll());
            $randomClassroomId = rand(1, $maxIdClassroom);
            $user->setRoom($manager->getRepository(Classroom::class)->find($randomClassroomId));

            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);

            $manager->persist($user);
        }
        #endregion

        $manager->flush();
    }
}
