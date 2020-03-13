<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 13.03.20
 * Time: 14:41
 */

namespace Test;


use OticTools\Mw\AutoUnitMapperMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * Class AutoUnitMapperTest
 * @package Test
 * @internal
 */
class AutoUnitMapperTest extends TestCase
{

    public function testMapping()
    {
        $t = new AutoUnitMapperMiddleware([
            "°" => "deg",
            "A" => "a",
            "Hz" => "s^-1"
        ]);

        $this->assertEquals("s^-1", $t->mapUnit("Hz"));
        $this->assertEquals("[e-6]a", $t->mapUnit("µA"));
    }

}
