<?php

namespace App\Security\Voter\Movie;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieCreatedByVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [MovieVoterInterface::EDIT, MovieVoterInterface::DELETE])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return true;
        }

        /** @var Movie $subject */
        return $subject->getCreatedBy() === $user;
    }
}
