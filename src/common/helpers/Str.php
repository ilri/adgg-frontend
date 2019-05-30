<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-03-15 9:49 PM
 */

namespace common\helpers;


class Str
{
    /**
     * @param string $string
     * @return mixed
     */
    public static function removeWhitespace($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * @param string $string
     * @return null|string
     */
    public static function extractJsonFromString($string)
    {
        preg_match('~\{(?:[^{}]|(?R))*\}~', $string, $matches);
        if (!empty($matches))
            return $matches[0];
        return null;
    }

    /**
     * @param string $string
     * @return bool
     */
    public static function isEmpty($string): bool
    {
        return !($string == "0" || $string);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function removeNonNumericCharacters(string $string): string
    {
        return preg_replace("/[^0-9]/", "", $string);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateUUID()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    /**
     * @param string $string
     * @param bool $pluralize
     * @return string
     */
    public static function getInitials(string $string, bool $pluralize = true): string
    {
        $string = trim($string);
        $acronym = "";
        $words = preg_split("/\s+/", $string);
        foreach ($words as $w) {
            $acronym .= $pluralize ? strtoupper($w[0]) : $w[0];
        }
        return $acronym;
    }

}