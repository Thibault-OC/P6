<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $users = [
            1=>[
                'email' => 'admin@gmail.com',
                'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                'is_verified' => 1,
                'firstname' => 'admin',

            ],
             2=>[
                 'email' => 'antoine@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'antoine',
            ],
             3=>[
                 'email' => 'didier@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'didier',
            ],
             4=>[
                 'email' => 'sylvain@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'sylvain',
            ],
             5=>[
                 'email' => 'pierre@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'pierre',
            ],
             6=>[
                 'email' => 'claude@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'claude',
            ],
             7=>[
                 'email' => 'logan@gmail.com',
                 'password' => '$2y$13$g7i3W/D8yb8M1mcUTvYvue02DIAXpUz2zhdz.H5EDreTq3IyStiaK',
                 'is_verified' => 1,
                 'firstname' => 'logan',
            ],
        ];


            
            foreach($users as $key=> $value){
                $user = new User();
                $user->setCreated(new \DateTime(sprintf('-%d days', rand(1, 100))));
                $user->setUpdated(new \DateTime(sprintf('-%d days', rand(1, 100))));
                $user->setEmail($value['email']);
                $user->setPassword($value['password']);
                $user->setIsVerified($value['is_verified']);
                $user->setFirstname($value['firstname']);
                $user->setLastLoginAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
                $manager->persist($user);

                $this->addReference('user_'. $key, $user);
            }
        

        $manager->flush();
    }
}
