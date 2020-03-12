<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 18:20
 */


namespace OticTools;


use OticTools\Core\OticChain;
use OticTools\Core\OticConfig;
use OticTools\Mw\CsvEvntReaderMiddleware;
use OticTools\Mw\VerifyUnitsMiddleware;

$map = [
    0 => "ts",
    1 => "signal_name",
    2 => "mu",
    3 => "val"
];


$chain = new OticChain();
$chain->add(new CsvEvntReaderMiddleware(";", $map));
//$chain->add(new VerifyUnitsMiddleware());

OticConfig::SetMwChain($chain);
