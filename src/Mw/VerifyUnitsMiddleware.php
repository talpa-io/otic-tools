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
        $stream = phore_file(__DIR__ . "/../allowed_units.csv")->fopen("r");
        while ( ! $stream->feof()) {
            $line = $stream->freadcsv(1024, ";");
            if ($line === null)
                continue;
            if (count($line) === 1 && $line[0] == "")
                continue;
            if (substr($line[0], 0, 1) == "#")
                continue;
            if (count($line) < 3)
                throw new \InvalidArgumentException("Invalid unit specification in 'allowed_units.csv': Line requires >= 3 Columns." . print_r($line, true));
            $this->allowedUnits[$line[0]] = true;
        }
        $stream->fclose();
    }


    private $allowedUnits = [];


    protected function isValidBaseUnit(string $unit) : bool
    {
        return isset ($this->allowedUnits[$unit]);
    }


    public function isValidUnit(string $unit, string &$errUnit="") : bool
    {
        foreach (explode("/", $unit) as $unit) {
            if ( ! preg_match("/^(\[e(?<prefix>[\+\-][0-9])\])?(?<unit>[a-z_-]+)(\^(?<exp>[\+\-]?[0-9]+))?$/", $unit, $matches)) {
                $errUnit = $unit;
                return false;
            }

            $unit = $matches["unit"];

            if ( ! $this->isValidBaseUnit($unit)) {
                $errUnit = $unit;
                return false;
            }
        }
        return true;
    }


    private $checkedUnits = [];


    /**
     * Verify all units follow the schema
     *
     * a/[e-3]sec^2
     *
     * Replaces invalid units with "--"
     *
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        $unit = $data["mu"];

        // Cache check result
        if ( ! isset ($this->checkedUnits[$unit])) {
            $this->checkedUnits[$unit] = $this->isValidUnit($unit, $errUnit);
            $this->stats->warn("Unit '$unit' not allowed [Subunit: '$errUnit']- stripped. (Sensor: '{$data["signal_name"]}')");
        }


        if ($this->checkedUnits[$unit] === false) {
            // rewrite to
            $data["mu"] = "--";

        }
        $this->next->message($data);
    }
}
