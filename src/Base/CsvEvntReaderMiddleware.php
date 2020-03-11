<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 18:06
 */

namespace OticTools\Base;


use OticTools\AbstractOticMiddleware;

class CsvEvntReaderMiddleware extends AbstractOticMiddleware
{

    private $delimiter;
    private $map;

    public function __construct(string $delimiter, array $map)
    {
        $this->delimiter = $delimiter;
        $this->map = $map;
    }


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $inFile = phore_file($data["in_file"]);
        $stream = $inFile->fopen("r");

        while ( ! $stream->feof()) {
            $line = $stream->freadcsv(0, $this->delimiter);
            if ($line === null)
                continue;

            if (count($line) == 0)
                continue; // Ingore empty lines
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
