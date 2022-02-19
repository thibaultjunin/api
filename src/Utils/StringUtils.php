<?php

namespace Thibaultjunin\Api\Utils;

class StringUtils
{

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     * @see https://stackoverflow.com/a/834355/8353011
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }


    /**
     * @param $haystack
     * @param $needle
     * @return bool
     * @see https://stackoverflow.com/a/834355/8353011
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }


    /**
     * @param $indicated
     * @param $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function after($indicated, $inthat)
    {
        if (!is_bool(strpos($inthat, $indicated))) {
            return substr($inthat, strpos($inthat, $indicated) + strlen($indicated));
        }
        return false;
    }


    /**
     * @param $indicated
     * @param $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function after_last($indicated, $inthat)
    {
        if (!is_bool(self::strrevpos($inthat, $indicated))) {
            return substr($inthat, self::strrevpos($inthat, $indicated) + strlen($indicated));
        }
        return false;
    }


    /**
     * @param $indicated
     * @param string $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function before($indicated, string $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $indicated));
    }


    /**
     * @param $indicated
     * @param $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function before_last($indicated, $inthat)
    {
        return substr($inthat, 0, self::strrevpos($inthat, $indicated));
    }


    /**
     * @param $indicated
     * @param $that
     * @param $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function between($indicated, $that, $inthat)
    {
        return self::before($that, self::after($indicated, $inthat));
    }


    /**
     * @param $indicated
     * @param $that
     * @param $inthat
     * @return false|string
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function between_last($indicated, $that, $inthat)
    {
        return self::after_last($indicated, self::before_last($that, $inthat));
    }

    /**
     * use strrevpos function in case your php version does not include it
     * @param $instr
     * @param $needle
     * @return false|int
     * @see https://www.php.net/manual/fr/function.substr.php#112707
     */
    public static function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) {
            return false;
        } else {
            return strlen($instr) - $rev_pos - strlen($needle);
        }
    }
}