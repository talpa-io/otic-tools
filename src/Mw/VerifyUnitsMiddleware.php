<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 10:26
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class VerifyUnitsMiddleware extends AbstractOticMiddleware
{


    public function __construct()
    {
        parent::__construct();
    }


    private $allowedUnits = [
        "deg" => ["min"=>-360, "max"=>360],
        "rpm" => true,
        "deg_c" => true,
        "bool"  => ["min"=>0, "max"=>1]
    ];

    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $unit = $data["mu"];
        if ( ! isset ($this->allowedUnits[$unit])) {
            $this->stats->skip("Unit '$unit' not allowed (Sensor: '{$data["signal_name"]}')");
            $this->stats->statsIncr("verify_unit.skipped");
            return;
        }
        $this->next->message($data);
    }
}
