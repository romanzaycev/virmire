<?php declare(strict_types = 1);

namespace Virmire\Events;

use Virmire\Collections\Collection;
use Virmire\Collections\TypedCollection;

/**
 * Class Emitter
 *
 * @package Virmire\Events
 */
class Emitter extends AbstractEventElement
{
    /**
     * @var Collection
     */
    private $listeners;
    
    /**
     * Emitter constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->listeners = new Collection();
    }
    
    /**
     * Add event listener.
     *
     * @param string $eventName
     * @param Listener $listener
     */
    protected function addListener(string $eventName, Listener $listener)
    {
        if (!$this->listeners->has($eventName)) {
            $this->listeners->addItem(
                $eventName,
                new class(Listener::class) extends TypedCollection
                {
                    public function getItem($key) : Listener
                    {
                        return parent::getItem($key);
                    }
                }
            );
        }
        
        $this->listeners->getItem($eventName)->addItem($listener->getUniqId(), $listener);
    }
    
    /**
     * @param Listener $listener
     *
     * @return void
     * @throws \Virmire\Collections\Exceptions\CollectionInvalidKeyException
     */
    protected function removeListener(Listener $listener)
    {
        $this->listeners->getItem($listener->getEventName())->retain($listener);
    }
    
    /**
     * Fire the event.
     *
     * @param string $eventName Event name
     * @param mixed|null $data
     *
     * @return bool
     */
    public function emit(string $eventName, &$data = null) : bool
    {
        $isPrevented = false;
        
        if ($this->listeners->has($eventName)) {
            foreach ($this->listeners->getItem($eventName) as $listener) {
                $isPrevented = $this->fire($listener, $data);
                
                if ($isPrevented) {
                    break;
                }
            }
        }
        
        return $isPrevented;
    }
    
    /**
     * @param Listener $listener
     * @param $data
     *
     * @return bool
     */
    private function fire(Listener $listener, $data) : bool
    {
        $isPrevented = false;
        
        $proxy = function () use ($listener, $data, &$isPrevented) {
            $isPrevented = $listener->onEmit($data);
        };
        $proxy->call($listener);
        
        return $isPrevented;
    }
}
