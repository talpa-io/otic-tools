<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 13.03.20
 * Time: 12:16
 */

namespace Test;


use OticTools\Mw\VerifyUnitsMiddleware;
use PHPUnit\Framework\TestCase;

class VerifyUnitsTest extends TestCase
{

    public function testUnits()
    {
        $t = new VerifyUnitsMiddleware();
        // Test combined types
        $this->assertTrue($t->isValidUnit("m/s"));

        // Test Prefix
        $this->assertTrue($t->isValidUnit("[e-3]s"));
        $this->assertTrue($t->isValidUnit("[e+3]s"));
        $this->assertTrue($t->isValidUnit("s"));

        // Test Exp
        $this->assertTrue($t->isValidUnit("s^1"));
        $this->assertTrue($t->isValidUnit("s^-1"));
        $this->assertTrue($t->isValidUnit("s^+1"));

        // Single Unit
        $this->assertTrue($t->isValidUnit("s"));

        // Multi char
        $this->assertTrue($t->isValidUnit("deg^-1"));

        // complex combined type: kilometer per cubic second
        $this->assertTrue($t->isValidUnit("[e+3]m/s^3"));
    }

}
