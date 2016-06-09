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

    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}