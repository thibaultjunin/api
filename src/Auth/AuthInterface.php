<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth;

interface AuthInterface
{

    /**
     * Authenticate a provided token.
     *
     * This method should return a UserInterface instance when the authentication is successful, null otherwise
     *
     * @param string $token
     * @return UserInterface|null
     */
    public function authenticate(string $token): ?UserInterface;

}