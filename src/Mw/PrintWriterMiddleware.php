<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 12:55
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;
use Phore\FileSystem\PhoreFile;

class PrintWriterMiddleware extends AbstractOticMiddleware
{
    /**
     * @var PhoreFile
     */
    private $file;

    /**
     * @var \Phore\FileSystem\FileStream
     */
    private $fh;

    public function __construct(string $outputFileName)
    {
        $this->file = new PhoreFile($outputFileName);
        $this->fh = $this->file->fopen("w+");
    }


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $this->fh->write(implode(";", [$data["ts"], $data["signal_name"], $data["mu"], $data["val"]]) . "\n");
    }

    public function onClose()
    {
        parent::onClose();
        $this->fh->close();
    }
}
