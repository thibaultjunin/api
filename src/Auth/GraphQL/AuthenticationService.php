<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use TheCodingMachine\GraphQLite\Security\AuthenticationServiceInterface;

class AuthenticationService implements AuthenticationServiceInterface
{

    private string $user;

    /**
     * @param string $user
     */
    public function __construct(string $user)
    {
        $this->user = $user;
    }

    public function isLogged(): bool
    {
        // TODO: Implement isLogged() method.
    }

    public function getUser(): ?object
    {
        // TODO: Implement getUser() method.
    }
}