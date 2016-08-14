<?php

namespace Tests\SMP3Bundle\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SMP3Bundle\Entity\User;


class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager) 
    {
        $user = new User();
        $user
            ->setUsername("testUser")
            ->setEmail('m@il.xx')
            ->setPlainPassword('testPass')
            ->setEnabled(true);
        
        $manager->persist($user);
        $manager->flush();
    }
}