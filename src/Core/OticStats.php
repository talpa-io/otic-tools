<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 13:11
 */

namespace OticTools\Core;


class OticStats
{


    private $warnings = [];
    private $skip = [];
    private $stats = [];


    public function warn($msg)
    {
        if ( ! isset ($this->warnings[$msg]))
            $this->warnings[$msg] = 0;
        $this->warnings[$msg]++;
    }

    public function skip($msg)
    {
        if ( ! isset ($this->skip[$msg]))
            $this->skip[$msg] = 0;
        $this->skip[$msg]++;
    }

    public function statsIncr($name)
    {
        if ( ! isset ($this->stats[$name]))
            $this->stats[$name] = 0;
        $this->stats[$name]++;
    }

    public function printStats()
    {
        $ret = "Warnings:";
        foreach ($this->warnings as $name => $num)
            $ret .= "\n$num: $name";

        $ret .= "\n\nSkipped data:";
        foreach ($this->skip as $name => $num)
            $ret .= "\n$num: $name";

        $ret .= "\n\nStats:";
        foreach ($this->stats as $name => $num)
            $ret .= "\n$name: $num";

        $ret .= "\n\n";
        return $ret;
    }

}
