<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Class Listener
 *
 * @package Virmire\Events
 */
class Listener extends AbstractEventSystem
{
    
    /**
     * @var \Closure
     */
    private $callback;
    
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
     * @param mixed $data
     *
     * @return bool
     */
    protected function onEmit(&$data) : bool
    {
        $isPrevented = false;
        $callback = $this->callback;
        
        if ($this->hasContext()) {
            $callback->call($this->getContext(), $data, $isPrevented);
        } else {
            $callback($data, $isPrevented);
        }
        
        return $isPrevented;
    }
}
