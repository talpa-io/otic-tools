<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 13.03.20
 * Time: 13:30
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class AutoUnitMapperMiddleware extends AbstractOticMiddleware
{


    private $units = [];

    private $prefix = [
        "µ" => "[e-6]",
        "u" => "[e-6]",
        "m" => "[e-3]"
    ];


    public function __construct(array $unitMap)
    {
        parent::__construct();
        $this->units = $unitMap;
    }


    protected function replacePreUnitExp(string $unit)
    {
        $unitNew = "";
        if ( ! preg_match("/^(?<prefix>[umµ])?(?<unit>[A-Za-z°]+)(?<exp>[²³])?$/u", $unit, $matches)) {
            return null;
        }

        if (isset($matches["prefix"])) {
            if ( ! isset ($this->prefix[$matches["prefix"]]))
                return null;
            $unitNew = $this->prefix[$matches["prefix"]];
        }

        if ( ! isset ($this->units[$matches["unit"]])) {
            return null;
        }
        $unitNew .= $this->units[$matches["unit"]];

        if (isset($matches["exp"])) {
            if ($matches["exp"] == "²")
                $unitNew .= "^2";
            if ($matches["exp"] == "³")
                $unitNew .= "^3";
        }
        return $unitNew;
    }


    protected function mapSingleUnit(string $unit) : string
    {
        $unit = trim($unit);
        if (isset ($this->units[$unit]))
            return $this->units[$unit];

        $newUnit = $this->replacePreUnitExp($unit);
        if ($newUnit === null)
            return $unit; // the original value

        return $newUnit;
    }


    public function mapUnit(string $unit) : string
    {
        $units = explode("/", $unit);
        for($i=0; $i<count($units); $i++)
            $units[$i] = $this->mapSingleUnit($units[$i]);
        return implode("/", $units);
    }



    private $mappedUnits = [];


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $mu = $data["mu"];
        if ( ! isset ($this->mappedUnits[$mu])) {
            $this->mappedUnits[$mu] = $this->mapUnit($mu);
        }

        return $this->mappedUnits[$mu];
    }
}
