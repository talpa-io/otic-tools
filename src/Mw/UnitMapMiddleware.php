<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 20:59
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class UnitMapMiddleware extends AbstractOticMiddleware
{

    private $map;

    public function __construct(array $map)
    {
        parent::__construct();
        $this->map = $map;
    }


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        if (isset ($this->map[$data["mu"]]))
            $data["mu"] = $this->map[$data["mu"]];
        $this->next->message($data);
    }
}
