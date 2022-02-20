<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use ReflectionException;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;
use Thibaultjunin\Api\Api;

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

    /**
     * @throws ReflectionException
     */
    public function isAllowed(string $right, $subject = null): bool
    {
        $user = Api::getInstance()->getUserInstance($this->user);
        return in_array($right, $user->getRoles());
    }
}