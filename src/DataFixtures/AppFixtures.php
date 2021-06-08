<?php

namespace App\DataFixtures;

use App\Entity\Channels;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $user = (new User())
            ->setEmail('john.doe@test.fr')
            ->setUsername($faker->userName())
        ;

        $user->setPassword($this->userPasswordEncoder->encodePassword($user, "password"));

        $manager->persist($user);

        $user = (new User())
            ->setEmail('jane.xxx@test.fr')
            ->setUsername($faker->userName())
            ->setGoogleId('googleid'.$faker->time())
        ;

        $user->setPassword($this->userPasswordEncoder->encodePassword($user, "password"));

        $manager->persist($user);
        
        for ($i = 0; $i < 5; $i++) {
            $channel = (new Channels())
                ->setTitle($faker->name())
                ->setDescription($faker->text())
                ->setYoutubeCustomUrl($faker->imageUrl())
            ;
            $manager->persist($channel);
        }

        $manager->flush();
    }
}
