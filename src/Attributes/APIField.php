<?php
/*
 * Copyright (c) 2021. SASU STAN-TAB CORP FRANCE / STAN-TAB CORP. LTD.
 * Tous droits réservés / All Rights Reserved
 */

namespace Thibaultjunin\Api\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class APIField
{

    const REQUIRED = 0;
    const NOT_EMPTY = 1;
    const NOT_NULL = 2;
    const SLUG = 4;
    const URL = 8;
    const EMAIL = 16;
    const ARRAY = 32;
    const INTEGER = 64;
    const FLOAT = 128;
    const BOOLEAN = 256;
    const ALPHANUMERICAL = 512;

    private int $requirements;

    /**
     * @param int $requirements
     */
    public function __construct(int $requirements)
    {
        $this->requirements = $requirements;
    }

    /**
     * @return int
     */
    public function getRequirements(): int
    {
        return $this->requirements;
    }


    public static function isFlagSet($flags, $flag)
    {
        return (($flags & $flag) == $flag);
    }

}