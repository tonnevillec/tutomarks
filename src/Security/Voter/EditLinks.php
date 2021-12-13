<?php

namespace App\Security\Voter;

use App\Entity\Links;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EditLinks implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (!$subject instanceof Links) {
            return self::ACCESS_ABSTAIN;
        }

        if (!in_array('EDIT', $attributes, true)) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();
        if (!$user instanceof Users) {
            return self::ACCESS_DENIED;
        }

        if ($user !== $subject->getPublishedBy()) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }
}
