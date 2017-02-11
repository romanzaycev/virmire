<?php declare(strict_types = 1);

namespace Virmire\Events;

use Virmire\Collections\Collection;
use Virmire\Collections\TypeCollection;

/**
 * Class Emitter
 *
 * @package Virmire\Events
 */
class Emitter extends AbstractEventSystem
{
    
    /**
     * @var Collection
     */
    private $listeners = [];
    
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
     * @param string $event
     * @param Listener $listener
     */
    protected function addListener(string $event, Listener $listener)
    {
        if (!$this->listeners->has($event)) {
            $this->listeners->addItem($event, new class(Listener::class) extends TypeCollection
            {
            });
        }
        
        $this->listeners->getItem($event)->addItem($listener->getUniqId(), $listener);
    }
    
    /**
     * Fire the event.
     *
     * @param string $event
     * @param mixed|null $data
     *
     * @return bool
     */
    public function emit(string $event, &$data) : bool
    {
        $isPrevented = false;
        
        if ($this->listeners->has($event)) {
            foreach ($this->listeners->getItem($event) as $listener) {
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
