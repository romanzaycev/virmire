<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Trait EventEmitterTrait
 *
 * @package Virmire\Events
 */
trait EventEmitterTrait
{
    /**
     * @var Dispatcher
     */
    private static $dispatcher;
    
    /**
     * @var Emitter
     */
    private static $emitter;
    
    /**
     * @throws \TypeError
     */
    private function initEmitter()
    {
        static::$dispatcher = Dispatcher::getInstance();
        static::$emitter = static::$dispatcher->register($this);
    }
    
    /**
     * Event subscription.
     *
     * @param string $event     Event name
     * @param \Closure $handler Event handler
     * @param object $bindTo    Bind context
     *
     * @return Listener
     */
    public function on(string $event, \Closure $handler, $bindTo = null)
    {
        return static::$dispatcher->on($this, $event, new Listener($handler), $bindTo);
    }
    
    /**
     * Emit event.
     *
     * @param string $event Event name
     * @param mixed $data   Event date
     *
     * @return bool
     */
    protected function emit(string $event, &$data = null) : bool
    {
        return static::$emitter->emit($event, $data);
    }
}