<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;

class AuthorizationService implements AuthorizationServiceInterface
{

    private string $user;

    /**
     * @param string $user
     */
    public function __construct(string $user)
    {
        $this->user = $user;
    }

    public function isAllowed(string $right, $subject = null): bool
    {
        // TODO: Implement isAllowed() method.
    }
}