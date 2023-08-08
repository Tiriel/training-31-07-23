<?php

namespace App\Security\Voter;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

#[AutoconfigureTag('security.voter', attributes: ['priority' => 300])]
class AdminVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes)
    {
        return \in_array('ROLE_ADMIN', $token->getRoleNames())
            ? self::ACCESS_GRANTED
            : self::ACCESS_ABSTAIN;
    }
}
