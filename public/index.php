<?php declare(strict_types = 1);

// @TODO: Move to Profiler
define('VIRMIRE_START', microtime(true));

require __DIR__ . '/../bootstrap/autoload.php';

use Virmire\Container;
use Virmire\Configuration;
use Virmire\Events\Dispatcher;
use Virmire\Application;
use Virmire\Http;

$application = new Application(
    new Container([
        'settings' => function () {
            return new Configuration(require_once __DIR__ . '/../config/config.php');
        },
        'eventDispatcher' => Dispatcher::getInstance()
    ])
);

$listener = new Virmire\Events\Listener(function (Http\Request $request) {
    var_dump($request->getIp());
    var_dump($request);
});
\Virmire\Events\Dispatcher::getInstance()->on(Application::class, 'onRequest', $listener);

$request = Http\Request::getInstance();
$response = $application->handle($request);
$application->done($request, $response);
