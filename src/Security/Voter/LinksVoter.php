<?php

namespace App\Security\Voter;

use App\Entity\Links;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class LinksVoter extends Voter
{
    public const LINK_EDIT = 'link_edit';

    protected function supports(string $attribute, $link): bool
    {
        return self::LINK_EDIT == $attribute
            && $link instanceof Links;
    }

    protected function voteOnAttribute(string $attribute, $link, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (null === $link->getPublishedBy()) {
            return false;
        }

        return match ($attribute) {
            self::LINK_EDIT => $this->canEdit($link, $user),
            default => false,
        };
    }

    private function canEdit(Links $link, UserInterface $user)
    {
        return $user === $link->getPublishedBy();
    }
}
