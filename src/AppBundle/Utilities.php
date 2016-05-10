<?php
/**
 * Created by PhpStorm.
 * User: OMAR-PC
 * Date: 10/05/2016
 * Time: 18:34
 */

namespace AppBundle;


class Utilities
{
    public static function matchWebsite($website, $str)
    {
        return strpos($str, $website);
    }

    public static function setNullIfStringEmpty($str)
    {
        return ($str == '') ? null : $str;
    }

    public static function removeWhitespace($str)
    {
        return preg_replace('/\s+/', '', $str);
    }

}