<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOrCreateFromGithubOauth(GithubResourceOwner $owner, UserPasswordEncoderInterface $passwordEncoder): User
    {
        /** @var User|null $user */
        $user = $this->createQueryBuilder('u')
            ->where('u.githubId = :githubId')
            ->orWhere('u.email = :email')
            ->setParameters([
                'githubId'  => $owner->getId(),
                'email'     => $owner->getEmail()
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if($user){
            if($user->getGithubId() === null) {
                $user->setGithubId($owner->getId());
                $this->_em->flush();
            }
            return $user;
        }

        $user = (new User())
            ->setGithubId($owner->getId())
            ->setEmail($owner->getEmail())
            ->setUsername($owner->getNickname())
            ->setPassword($passwordEncoder
                ->encodePassword(
                    new User(),
                    substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 10)
                )
            )
        ;
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function findOrCreateFromGoogleOauth(GoogleUser $googleUser, UserPasswordEncoderInterface $passwordEncoder): User
    {
        /** @var User|null $user */
        $user = $this->createQueryBuilder('u')
            ->where('u.googleId = :googleId')
            ->orWhere('u.email = :email')
            ->setParameters([
                'googleId'  => $googleUser->getId(),
                'email'     => $googleUser->getEmail()
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if($user){
            if($user->getGoogleId() === null) {
                $user->setGoogleId($googleUser->getId());
                $this->_em->flush();
            }
            return $user;
        }

        $user = (new User())
            ->setGoogleId($googleUser->getId())
            ->setEmail($googleUser->getEmail())
            ->setUsername($googleUser->getName())
            ->setPassword($passwordEncoder
                ->encodePassword(
                    new User(),
                    substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 10)
                )
            )
        ;
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }
}
