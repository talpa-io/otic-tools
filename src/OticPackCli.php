<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 19:32
 */

namespace OticTools;


use OticTools\Core\OticConfig;
use OticTools\Mw\OticWriterMiddleware;
use OticTools\Mw\PrintWriterMiddleware;

class OticPackCli
{


    private static function printHelp()
    {
        echo <<<EOT

Open Telemetry Interchange Container - Packager and Formating

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

    public static function run()
    {
        $opts = phore_getopt("hdvi:o:m:");

        if ($opts->has("h")) {
            self::printHelp();
            exit (127);
        }

        if ($opts->has("v")) {
            echo "Libotic version: " . \Otic\getLibOticVersion() . "\n";
            exit(127);
        }


        $middleWareFile = $opts->get("m", __DIR__ . "/../src/default_csvevt_mw.php");
        require $middleWareFile;


        $inputFile = $opts->get("i", "php://stdin");
        if ($inputFile === "php://stdin" && posix_isatty(STDIN)) {
            self::printHelp();
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

    }


}
