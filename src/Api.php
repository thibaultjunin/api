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
    private bool $devMode = true;
    private ?string $cacheFolder = null;

    /**
     * @return string|null
     */
    public function getCacheFolder(): ?string
    {
        return $this->cacheFolder;
    }

    /**
     * @param string|null $cacheFolder
     */
    public function setCacheFolder(?string $cacheFolder): void
    {
        $this->cacheFolder = $cacheFolder;
    }

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
     * @return bool
     */
    public function isDevMode(): bool
    {
        return $this->devMode;
    }

    /**
     * @param bool $devMode
     */
    public function setDevMode(bool $devMode): void
    {
        $this->devMode = $devMode;
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