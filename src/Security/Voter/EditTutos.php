<?php
namespace App\Security\Voter;

use App\Entity\Tutos;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EditTutos implements VoterInterface
{

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if(!$subject instanceof Tutos) {
            return self::ACCESS_ABSTAIN;
        }

        if(!in_array('EDIT', $attributes, true)) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();
        if(!$user instanceof User){
            return self::ACCESS_DENIED;
        }

        if($user !== $subject->getPublishedBy()) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }
}