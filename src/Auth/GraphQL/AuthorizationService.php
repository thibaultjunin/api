<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;
use Thibaultjunin\Api\Auth\UserInterface;

class AuthorizationService implements AuthorizationServiceInterface
{

    private ?UserInterface $user;

    /**
     * @param UserInterface|null $user
     */
    public function __construct(?UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $right
     * @param $subject
     * @return bool
     */
    public function isAllowed(string $right, $subject = null): bool
    {
        if ($this->user == null) {
            return false;
        }
        return in_array($right, $this->user->getRoles());
    }
}