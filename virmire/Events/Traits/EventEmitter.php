<?php declare(strict_types = 1);

namespace Virmire\Events\Traits;

use Virmire\Events\Dispatcher;
use Virmire\Events\Emitter;
use Virmire\Events\Listener;

/**
 * Trait EventEmitter
 *
 * @package Virmire\Events\Traits
 */
trait EventEmitter
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
    public function on(string $event, \Closure $handler, $bindTo = null) : Listener
    {
        $listener = new Listener($handler);
        static::$dispatcher->on($this, $event, $listener, $bindTo);
        return $listener;
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