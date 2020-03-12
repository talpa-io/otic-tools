<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 13:47
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;
use OticTools\Core\OticMiddleware;
use OticTools\Core\OticStats;

class NullWriterMiddelware extends AbstractOticMiddleware
{


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $this->stats->statsIncr("writer.total.written");
    }
}
