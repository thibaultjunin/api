<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use TheCodingMachine\GraphQLite\Security\AuthenticationServiceInterface;
use Thibaultjunin\Api\Auth\UserInterface;


class AuthenticationService implements AuthenticationServiceInterface
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
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        if (!$this->isLogged()) {
            return null;
        }
        return $this->user;
    }

    /**
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->user != null;
    }
}