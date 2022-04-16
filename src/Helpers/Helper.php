<?php
/*
 * Copyright (c) 2022 Thibault JUNIN.
 */

namespace Thibaultjunin\Api\Helpers;


use ArrayAccess;
use DateTime;
use DateTimeInterface;
use JsonSerializable;

abstract class Helper implements ArrayAccess, JsonSerializable
{
    private ?string $uuid = null;
    private ?DateTime $created_at = null;
    private ?DateTime $updated_at = null;

    /**
     * @param $uuid
     * @return Helper|null
     */
    public abstract function get($uuid): ?self;

    /**
     * @param int $page
     * @param int $per_page
     * @return array|null
     */
    public abstract function list(int $page = 1, int $per_page = 10): ?array;

    /**
     * @return int
     */
    public abstract function count(): int;

    /**
     * @return bool
     */
    public abstract function save(): bool;

    /**
     * @return bool
     */
    public abstract function delete(): bool;

    public function __serialize(): array
    {
        return $this->jsonSerialize();
    }

    public function jsonSerialize(): mixed
    {
        return array_merge([
            "uuid" => $this->getUuid(),
            "created_at" => $this->getCreatedAt()->format(DateTimeInterface::ISO8601),
            "updated_at" => $this->getUpdatedAt()->format(DateTimeInterface::ISO8601),
        ], $this->serialize());
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime|null $created_at
     */
    public function setCreatedAt(?DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime|null $updated_at
     */
    public function setUpdatedAt(?DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public abstract function serialize(): array;

    public function offsetExists($offset): bool
    {
        return $this->__isset($offset);
    }

    public function __isset(string $name): bool
    {
        return match ($name) {
            "uuid" => $this->getUuid() != NULL,
            "created_at" => $this->getCreatedAt() != NULL,
            "updated_at" => $this->getUpdatedAt() != NULL,
            default => $this->propertyExists($name),
        };
    }

    public abstract function propertyExists($property): bool;

    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    public function __get(string $name)
    {
        return match ($name) {
            "uuid" => $this->getUuid(),
            "created_at" => $this->getCreatedAt(),
            "updated_at" => $this->getUpdatedAt(),
            default => $this->getProperty($name),
        };
    }

    public function __set(string $name, $value): void
    {
        switch ($name) {
            case "uuid":
                $this->setUuid($value);
                break;
            default:
                $this->setProperty($name, $value);
                break;
        }
    }

    public abstract function getProperty($property);

    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->__unset($offset);
    }

    public function __unset(string $name): void
    {
        $this->__set($name, NULL);
    }

    public abstract function setProperty($property, $value);
}