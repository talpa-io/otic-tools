<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 13:55
 */

namespace Test;

use OticTools\Tools\OticDump;
use PHPUnit\Framework\TestCase;

class HttpConvertTest extends TestCase
{


    public function testConvertUrl()
    {
        $body = phore_http_request("http://localhost/v1/convert/M1123/CsvEvt")
            ->withPostBody(phore_file(__DIR__ .  "/csvdata.csv")->get_contents())
            ->send()->getBody();

        $data = OticDump::Dump($body, $stats);

        $this->assertEquals(100, count($data));
    }

    public function testCsvUrl()
    {
        $body = phore_http_request("http://localhost/v1/csv/M1123/CsvEvt")
            ->withPostBody(phore_file(__DIR__ .  "/csvdata.csv")->get_contents())
            ->send()->getBody();

        $this->assertEquals(101, count(explode("\n", $body)));
    }

    public function testDiagUrl()
    {
        $body = phore_http_request("http://localhost/v1/diag/M1123/CsvEvt")
            ->withPostBody(phore_file(__DIR__ .  "/csvdata.csv")->get_contents())
            ->send()->getBody();

        $this->assertEquals(10, count(explode("\n", $body)));
    }

}
