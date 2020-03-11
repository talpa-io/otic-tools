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

/**
 * Class BenchGenCli
 * @package OticTools
 * @internal
 */
class BenchGenCli
{


    private static function printHelp()
    {
        echo <<<EOT

Csv Generator for benchmarking

Usage:

    {$_SERVER['argv'][0]} <arguments>
    
Parameters:

    -h          Print Help
    -v          Print Version
    -n <num>    Number of records to create
    -s <num>    Number of sensors to create
    -o <file>   Output to file (default: stdout)
 
EOT;
        echo "\n\n";

    }

    public static function run()
    {
        $opts = phore_getopt("hdvo:n:s:");

        if ($opts->has("h")) {
            self::printHelp();
            exit (127);
        }

        if ($opts->has("v")) {
            echo "Libotic version: " . \Otic\getLibOticVersion() . "\n";
            exit(127);
        }



        $outputFile = $opts->get("o", "php://stdout");
        if ($outputFile == "php://stdout" && posix_isatty(STDOUT)) {

        }

        $outputFile = phore_file($outputFile);
        $outputStream = $outputFile->fopen("w+");

        $ppi = $opts->get("n", 10);
        $pps = $opts->get("s", 10);
        $starttime = strtotime("2018-01-01");

        for ($i = 0; $i<$ppi; $i++) {
            for ($p = 0; $p < $pps; $p++) {
                $cur = [
                    $starttime + $i/10,
                    "sen_" . md5($p),
                    "mu_$p",
                    $i + $p
                ];

                $outputStream->write(implode(";", $cur) . "\n");
            }
        }
        $outputStream->fclose();
    }


}
