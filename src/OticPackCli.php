<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 19:32
 */

namespace OticTools;


use OticTools\Core\OticConfig;
use OticTools\Core\OticStats;
use OticTools\Mw\NullWriterMiddelware;
use OticTools\Mw\OticWriterMiddleware;
use OticTools\Mw\PrintWriterMiddleware;

/**
 * Class OticPackCli
 * @package OticTools
 * @internal
 */
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
    -s          Print statistics and warnings
EOT;
        echo "\n\n";

    }

    public static function run()
    {
        $opts = phore_getopt("hdvi:o:m:s");

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

        $chain = OticConfig::GetMwChain();
        $stats = null;
        if ($opts->has("s")) {
            $chain->add(new NullWriterMiddelware());
            $chain->getFirst()->setStats($stats = new OticStats());
        } elseif ($opts->has("d")) {
            $chain->add(new PrintWriterMiddleware($outputFile));
        } else {
            $chain->add(new OticWriterMiddleware($outputFile));
        }

        $chain->getFirst()->message(["file_in" => $inputFile]);
        $chain->getFirst()->onClose();

        if ($stats !== null)
            echo $stats->printStats();

    }


}
