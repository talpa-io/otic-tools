<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 19:32
 */

namespace OticTools;


use Otic\OticReader;
use OticTools\Core\OticConfig;
use OticTools\Mw\OticWriterMiddleware;
use OticTools\Mw\PrintWriterMiddleware;

class OticDumpCli
{


    private static function printHelp()
    {
        echo <<<EOT

Open Telemetry Interchange Container - Dump otic file

Usage:

    {$_SERVER['argv'][0]} <arguments>
    
Parameters:

    -h          Print Help
    -v          Print Version
    -i <file>   Input file (default: stdin)
    -o <file>   Output to file (default: stdout)
 
EOT;
        echo "\n\n";

    }

    public static function run()
    {
        $opts = phore_getopt("hdvi:o:");

        if ($opts->has("h")) {
            self::printHelp();
            exit (127);
        }

        if ($opts->has("v")) {
            echo "Libotic version: " . \Otic\getLibOticVersion() . "\n";
            exit(127);
        }


        $inputFile = $opts->get("i", "php://stdin");
        if ($inputFile === "php://stdin" && posix_isatty(STDIN)) {
            self::printHelp();
            exit (127);
        }


        $outputFile = $opts->get("o", "php://stdout");
        if ($outputFile == "php://stdout" && posix_isatty(STDOUT)) {

        }

        $outputFile = phore_file($outputFile);
        $outputStream = $outputFile->fopen("w+");

        $reader = new OticReader();
        $reader->open($inputFile);
        $stats = [
            "records" => 0,
            "first_ts" => null,
            "last_ts" => null,
        ];
        $reader->setOnDataCallback(function ($timestamp, $colname, $unit, $value) use ($outputStream) {
            $outputStream->write(implode(";", [$timestamp, $colname, $unit, $value]) . "\n");
        });
        $reader->read();

    }


}
