#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 12:23
 */

namespace OticTools;

use OticTools\Base\OticWriterMiddleware;
use OticTools\Base\PrintWriterMiddleware;

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require __DIR__ . "/../vendor/autoload.php";
} else {
    require __DIR__ . "/../../../autoload.php";
}


function print_help() {
    echo <<<EOT

Usage:

    {$_SERVER['argv'][0]} <arguments>
    
Parameters:

    -h          Print Help
    -v          Print Version
    -i <file>   Input file (default: stdin)
    -m <file>   Load Middleware file (required)
    -o <file>   Output to file (default: stdout)
    -d          Output readable converted information
EOT;
    echo "\n\n";

}



$opts = phore_getopt("hdvi:o:m:");


if ($opts->has("h")) {
    print_help();
    exit (127);
}


$middleWareFile = $opts->get("m", __DIR__ . "/../src/default_csvevt_mw.php");
require $middleWareFile;


$inputFile = $opts->get("i", "php://stdin");
if ($inputFile === "php://stdin" && posix_isatty(STDIN)) {
    print_help();
    exit (127);
}


$outputFile = $opts->get("o", "php://stdout");
if ($outputFile == "php://stdout" && posix_isatty(STDOUT)) {

}


$middleware = OticConfig::GetWriterMiddleWareSource();
if ($opts->has("d")) {
    $middleware->setNext(new PrintWriterMiddleware($outputFile));
} else {
    $middleware->setNext(new OticWriterMiddleware($outputFile));
}


$middleware->message(["in_file" => $inputFile]);
$middleware->onClose();






