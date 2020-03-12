<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 09:52
 */

namespace App;

use OticTools\OticConvModule;
use Phore\MicroApp\App;
use Phore\MicroApp\Handler\JsonExceptionHandler;

require __DIR__ . "/../vendor/autoload.php";

/**
 * Add Middleware dtype mappings here
 */
$middleWareMap = [
    "CsvEvt" => __DIR__ . "/../src/default_csvevt_mw.php"

];


$app = new App();
$app->acl->addRule(aclRule()->route("/*")->ALLOW());
$app->addModule(new OticConvModule($middleWareMap));

$app->serve();
