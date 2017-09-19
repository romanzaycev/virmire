<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Class Listener
 *
 * @package Virmire\Events
 */
class Listener extends EventElement
{
    /**
     * @var \Closure
     */
    private $callback;

    /**
     * @var Emitter
     */
    private $emitter;

    /**
     * @var string
     */
    private $eventName;

    /**
     * Listener constructor.
     *
     * @param \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        parent::__construct();

        $this->callback = $callback;
    }

    /**
     * @return void
     */
    public function unbind()
    {
        // @TODO: Rethink this place, protected-hack should to be removed
        $self = $this;
        $proxy = function () use ($self) {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->removeListener($self);
        };
        $proxy->call($this->emitter);
    }

    /**
     * @param Emitter $emitter
     */
    protected function setEmitter(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param string $eventName
     */
    protected function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getEventName() : string
    {
        return $this->eventName;
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    protected function onEmit(&$data) : bool
    {
        $callback = $this->callback;

        if ($this->hasContext()) {
            $result = $callback->call($this->getContext(), $data);
        } else {
            $result = $callback($data);
        }

        return !($result);
    }
}
