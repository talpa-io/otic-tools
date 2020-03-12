<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 19:26
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class GzipUnpackerMiddleware extends AbstractOticMiddleware
{


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        // TODO: Implement message() method.
    }
}
