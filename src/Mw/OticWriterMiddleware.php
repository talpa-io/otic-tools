<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 12:54
 */

namespace OticTools\Mw;


use Otic\OticWriter;
use OticTools\Core\AbstractOticMiddleware;

class OticWriterMiddleware extends AbstractOticMiddleware
{

    protected $oticWriter;

    public function __construct(string $outputFile = "php://stdout")
    {
        parent::__construct();
        $this->oticWriter = new OticWriter();
        $this->oticWriter->open($outputFile);
    }

    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $this->oticWriter->inject($data["ts"], $data["signal_name"], $data["val"], $data["mu"]);
    }

    public function onClose()
    {
        parent::onClose();
        $this->oticWriter->close();
    }
}
