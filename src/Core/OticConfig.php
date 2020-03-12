<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 01.10.19
 * Time: 13:58
 */

namespace OticTools\Core;


class OticConfig
{
    private static $chain;

    public static function SetMwChain(OticChain $chain)
    {
        self::$chain = $chain;
    }


    public static function GetMwChain() : OticChain
    {
        if (self::$chain === null)
            throw new \InvalidArgumentException("No OticChain registered to OticConfig");
        return self::$chain;
    }

}
