<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
{
    $faker = Faker\Factory::create('fr_FR');



    for($nbComment = 1; $nbComment <= 100; $nbComment++){

        $user = $this->getReference('user_'. $faker->numberBetween(1, 7));
        $trick = $this->getReference('trick_'. $faker->numberBetween(1, 16));

        $comment = new Comment();

        $comment->setUser($user);
        $comment->setTrick($trick );

        $comment->setComment($faker->realText(100));
        $comment->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));


        $manager->persist($comment);



    }
    $manager->flush();
}

    public function getDependencies()
{
    return [
        TricksFixtures::class,
        UsersFixtures::class
    ];
}
}