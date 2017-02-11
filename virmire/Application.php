<?php declare(strict_types = 1);

namespace Virmire;

use Virmire\Events;
use Virmire\Interfaces\ContainerInterface;
use Virmire\Http\Request;
use Virmire\Http\Response;

/**
 * Class Application
 *
 * @package Virmire
 *
 * @property-read Configuration $settings Application settings
 * @property-read Events\Dispatcher $eventDispatcher
 */
class Application
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var Events\Emitter
     */
    private $emitter;
    
    /**
     * Application constructor.
     *
     * @param ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        
        $this->emitter = new Events\Emitter();
        $this->eventDispatcher->register($this, $this->emitter);
    }
    
    /**
     * Handle the request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $this->emitter->emit('onRequest', $request);
        
        return new Response();
    }
    
    /**
     * Terminate application.
     *
     * @param Request $request
     * @param Response $response
     */
    public function done(Request $request, Response $response)
    {
        // @TODO Move to Profiler
        define('VIRMIRE_END', microtime(true));
        if ($this->settings->get('app.profiler_enable', false) === true) {
            $time = VIRMIRE_END - VIRMIRE_START;
            $body = $response->getBody();
            if (strpos($body, '#virmire_page_generation_time#') !== false) {
                $response->setBody(
                    str_replace(
                        '#virmire_page_generation_time#',
                        $time,
                        $body
                    )
                );
            }
        }
        
        $response->writeHeaders();
        $response->writeBody();
    }
    
    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container->get($name);
    }
}
