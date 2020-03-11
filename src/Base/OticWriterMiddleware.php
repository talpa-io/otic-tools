<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 11.03.20
 * Time: 12:54
 */

namespace OticTools\Base;


use OticTools\AbstractOticMiddleware;

class OticWriterMiddleware extends AbstractOticMiddleware
{

    protected $oticWriter;

    public function __construct(string $outputFile = "php://stdout")
    {

    }

    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        echo "out";
    }
}
