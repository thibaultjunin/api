<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api;

use ReflectionClass;
use ReflectionException;
use Thibaultjunin\Api\Auth\AuthInterface;
use Thibaultjunin\Api\Exceptions\ClassNotFoundException;

class Api
{

    private static Api $instance;
    private AuthInterface $auth;
    private string $user;
    private bool $devMode = true;
    private ?string $cacheFolder = null;

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

    /**
     * @throws ReflectionException
     */
    public function getUserInstance($uuid): object
    {
        $ref = new ReflectionClass($this->user);
        return $ref->newInstanceArgs([$uuid]);
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @throws ClassNotFoundException
     */
    public function setUser(string $user): void
    {
        try {
            new ReflectionClass($this->user);
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException($this->user, $e);
        }
        $this->user = $user;
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