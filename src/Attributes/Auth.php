<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Auth
{
    const EVERYONE = "everyone";
    const ALL = "all";

    private array $roles = [];

    /**
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

}