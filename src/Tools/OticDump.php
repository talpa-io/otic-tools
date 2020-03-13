<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 21:23
 */

namespace OticTools\Tools;


use Otic\OticReader;

class OticDump
{


    public static function Dump(string $data, &$stats) : array
    {
        $tmpFile = phore_tempfile();
        $tmpFile->set_contents($data);

        $reader = new OticReader();
        $reader->open((string)$tmpFile);
        $stats = [
            "records" => 0,
            "first_ts" => null,
            "last_ts" => null,
        ];

        $data = [];
        $reader->setOnDataCallback(function ($timestamp, $colname, $unit, $value) use (&$data) {
            $data[] = [
                "ts" => $timestamp,
                "sig" => $colname,
                "mu" => $unit,
                "val" => $value
            ];
        });
        $reader->read();
        return $data;
    }



}
