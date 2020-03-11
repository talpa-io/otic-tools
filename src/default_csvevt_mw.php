<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 18:20
 */


namespace OticTools;


use OticTools\Base\CsvEvntReaderMiddleware;

$map = [
    0 => "ts",
    1 => "sig_name",
    2 => "mu",
    3 => "val"
];

$csvReader = new CsvEvntReaderMiddleware(";", $map);


OticConfig::AddWriterMiddleWare($csvReader);
