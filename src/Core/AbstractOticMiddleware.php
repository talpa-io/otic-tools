<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 01.10.19
 * Time: 14:08
 */

namespace OticTools\Core;


abstract class AbstractOticMiddleware implements OticMiddleware
{

    protected $stats;

    /**
     * @var OticMiddleware
     */
    protected $next;


    public function __construct()
    {
        $this->stats = new OticNullStats();
    }


    public function setNext(OticMiddleware $next)
    {
        $this->next = $next;
    }


    public function setStats(OticStats $stats)
    {
        $this->stats = $stats;
        if ($this->next !== null)
            $this->next->setStats($stats);
    }



    public function onClose()
    {
        if ($this->next !== null)
            $this->next->onClose();
    }

}
