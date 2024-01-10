<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter
{
    public const TITLE = 'book.title';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return ($subject instanceof Book || null === $subject)
            && \in_array($attribute, [self::TITLE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        assert($subject instanceof Book);

        return $user === $subject->getCreatedBy();
    }
}
