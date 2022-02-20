<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth\GraphQL;

use ReflectionException;
use TheCodingMachine\GraphQLite\Security\AuthenticationServiceInterface;
use Thibaultjunin\Api\Api;


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

    /**
     * @throws ReflectionException
     */
    public function getUser(): ?object
    {
        if (!$this->isLogged()) {
            return null;
        }
        return Api::getInstance()->getUserInstance($this->user);
    }

    public function isLogged(): bool
    {
        return $this->user != null;
    }
}