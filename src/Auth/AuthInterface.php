<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth;

interface AuthInterface
{

    public function authenticateToken(string $token): bool;

    public function getUserIdForToken(string $token): ?string;

    public function getRolesForToken(string $token): ?array;

}