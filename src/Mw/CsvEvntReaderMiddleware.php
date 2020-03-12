<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 18:06
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class CsvEvntReaderMiddleware extends AbstractOticMiddleware
{

    private $delimiter;
    private $map;

    public function __construct(string $delimiter, array $map)
    {
        parent::__construct();
        $this->delimiter = $delimiter;
        $this->map = $map;
    }


    /**
     *
     * Expects Message:
     *
     * ["in_file" => <filename to parse>]
     *
     * Sends Message:
     *
     * [
     *  "ts" => <timestamp float>
     *  "signal_name" => <signal_name:string>
     *  "mu" => <measurement unit:string>
     *  "val" => <value:mixed>
     * ]
     *
     * @param array|null $data
     * @return mixed
     * @throws \Phore\FileSystem\Exception\FileAccessException
     */
    public function message(array $data)
    {
        $inFile = phore_file($data["file_in"]);
        $stream = $inFile->fopen("r");

        while ( ! $stream->feof()) {

            $line = $stream->freadcsv(0, $this->delimiter);
            $this->stats->statsIncr("read.lines.csv.total");
            if ($line === null)
                continue;

            if (count($line) == 0)
                continue; // Ingore empty lines
            $this->stats->statsIncr("read.lines.csv.data");
            $msg = [];
            foreach ($this->map as $key => $val) {
                if (! isset ($line[$key]))
                    throw new \InvalidArgumentException("Invalid colum: Index $key not found in " . print_r($line, true));
                $msg[$val] = $line[$key];

            }
            $msg["ts"] = (float)$msg["ts"];

            $this->next->message($msg);
        }
        $stream->fclose();
    }

    public function onClose()
    {
        parent::onClose();

    }
}
