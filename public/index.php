<?php declare(strict_types = 1);

// @TODO: Move to Profiler
define('VIRMIRE_START', microtime(true));

require __DIR__ . '/../bootstrap/autoload.php';

use Virmire\Container;
use Virmire\Configuration;
use Virmire\Application;
use Virmire\Http;

$application = new Application(
    new Container([
        'settings' => function () {
            return new Configuration(require_once __DIR__ . '/../config/config.php');
        }
    ])
);

$application->on(
    'request',
    function (Http\Request $request){
        var_dump($request->getIp());
        var_dump($request);
    }
);

$request = Http\Request::getInstance();
$response = $application->handle($request);
$application->done($request, $response);
