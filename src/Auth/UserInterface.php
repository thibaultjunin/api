<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Auth;

interface UserInterface
{

    public function __construct(string $uuid);

    public function getRoles();

}