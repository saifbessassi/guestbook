<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    )
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $tunis = new Conference();
        $tunis->setCity('Tunis');
        $tunis->setYear('2020');
        $tunis->setIsInternational(false);
        $manager->persist($tunis);

        $paris = new Conference();
        $paris->setCity('Paris');
        $paris->setYear('2022');
        $paris->setIsInternational(true);
        $manager->persist($paris);

        $comment = new Comment();
        $comment->setConference($paris);
        $comment->setAuthor('xxx');
        $comment->setEmail('xxx@mail.com');
        $comment->setText('This is an example comment');
        $manager->persist($comment);

        $admin = new Admin();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(Admin::class)->hash('admin'));
        $manager->persist($admin);

        $manager->flush();
    }
}
