<?php

namespace App\Security\Voter\Movie;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieRatedVoter extends Voter
{

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === MovieVoterInterface::RATED && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Movie $subject */
        if ('G' === $subject->getRated()) {
            return true;
        }

        /** If user is not an entity, then it's the admin */
        $user = $token->getUser() ;
        if (!$user instanceof User) {
            return true;
        }

        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y;

        return match ($subject->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false
        };
    }
}
