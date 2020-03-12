<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 09:17
 */

namespace OticTools;


use OticTools\Core\OticConfig;
use OticTools\Core\OticStats;
use OticTools\Mw\NullWriterMiddelware;
use OticTools\Mw\OticWriterMiddleware;
use OticTools\Mw\PrintWriterMiddleware;
use Phore\MicroApp\App;
use Phore\MicroApp\AppModule;
use Phore\MicroApp\Handler\JsonExceptionHandler;
use Phore\MicroApp\Handler\JsonResponseHandler;

class OticConvModule implements AppModule {


    private $ctypeToMwMap;

    public function __construct(array $ctypeToMwMap)
    {
        $this->ctypeToMwMap = $ctypeToMwMap;
    }


    public function _loadMiddlewareByDtype($dtype)
    {
        if ( ! isset ($this->ctypeToMwMap[$dtype]))
            throw new \InvalidArgumentException("No middleware registered for dtype '$dtype'");
        $file = $this->ctypeToMwMap[$dtype];
        if ( ! phore_file($file)->exists())
            throw new \InvalidArgumentException("Middleware file '{$file}' not existing for dtype '$dtype'");
        require $file;
    }


    /**
     * Called just after adding this to a app by calling
     * `$app->addModule(new SomeModule());`
     *
     * Here is the right place to add Routes, etc.
     *
     * @param App $app
     *
     * @return mixed
     */
    public function register(App $app)
    {
        $app->activateExceptionErrorHandlers();
        $app->setOnExceptionHandler(new JsonExceptionHandler());
        $app->setResponseHandler(new JsonResponseHandler());
        set_time_limit(1200);

        $app->router->get("/", function () {
            return ["success"=>true, "msg"=>"Converter ready"];
        });

        $app->router->post("/v1/:ctype/:tmid?/:dtype?", function ($ctype, string $tmid=null, string $dtype=null) {
            $this->_loadMiddlewareByDtype($dtype);

            $outputFile = phore_file("php://stdout")->fopen("w")->getRessource();
            $stats = null;
            $chain = OticConfig::GetMwChain();
            switch ($ctype) {
                case "convert":
                    header("Content-Type: application/binary;");
                    $chain->add(new OticWriterMiddleware($outputFile));
                    break;

                case "csv":
                    header("Content-Type: text/csv; charset=utf-8");
                    $chain->add(new PrintWriterMiddleware($outputFile));
                    break;

                case "stats":
                    header("Content-Type: text/plain; charset=utf-8");
                    $chain->add(new NullWriterMiddelware());
                    $chain->getFirst()->setStats($stats = new OticStats());
                    break;

                default:
                    throw new \InvalidArgumentException("Invalid ctype '$ctype'");
            }



            $chain->getFirst()->message(["in_file" => "php://input"]);
            $chain->getFirst()->onClose();

            if ($stats !== null)
                echo $stats->printStats();

            return true;

        });
    }
}
