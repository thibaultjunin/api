<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api;

use Thibaultjunin\Api\Auth\AuthInterface;

class Api
{

    private static Api $instance;
    private AuthInterface $auth;


    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return Api
     */
    public static function getInstance(): Api
    {
        return self::$instance;
    }

    /**
     * @return AuthInterface
     */
    public function getAuth(): AuthInterface
    {
        return $this->auth;
    }

    /**
     * @param AuthInterface $auth
     */
    public function setAuth(AuthInterface $auth): void
    {
        $this->auth = $auth;
    }


}