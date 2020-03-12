<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 20:44
 */

namespace OticTools\Core;


class OticChain
{


    /**
     * @var null|OticMiddleware
     */
    private $first = null;

    /**
     * @var null|OticMiddleware
     */
    private $last = null;




    public function add(OticMiddleware $mw)
    {
        if ($this->first === null) {
            $this->first = $mw;
            $this->last = $mw;
            return $this;
        }
        $this->last->setNext($mw);
        $this->last = $mw;

    }


    public function getFirst() : ?OticMiddleware
    {
        return $this->first;
    }


    public function getLast() : ?OticMiddleware
    {
        return $this->last;
    }

}
