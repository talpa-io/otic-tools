<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 13:17
 */

namespace OticTools\Core;


class OticNullStats extends OticStats
{

    public function warn($msg)
    {
        return; // Do nothing
    }

    public function skip($msg)
    {
        return; // Do noting
    }

    public function statsIncr($name)
    {
        return; // Do nothing
    }

    public function statsVal($name, $val)
    {
        return; //do nothing
    }
}
