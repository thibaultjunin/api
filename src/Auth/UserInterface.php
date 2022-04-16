<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth;

interface UserInterface
{

    /**
     * Return an array of Roles
     *
     * This method must return an array of string containing the access the currently authenticated user has.
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * Return the user id
     *
     * This method should (when implemented) return a user id.
     *
     * @return string|null
     */
    public function getUserId(): ?string;

}