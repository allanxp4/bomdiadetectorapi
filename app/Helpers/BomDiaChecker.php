<?php
namespace App\Helpers;
/**
 * Created by PhpStorm.
 * User: Allan
 * Date: 20/08/2017
 * Time: 22:31
 */

class BomDiaChecker
{
    public static function check($string)
    {
        $string = mb_strtolower($string, 'UTF-8');
        if(str_contains($string, 'bom')){
            if(str_contains($string, ['dia', 'noite'])){
                return true;
            }
        }
        return false;
    }

}