<?php declare(strict_types = 1);

namespace Virmire\Events;

use Virmire\Traits\Singleton;
use Virmire\Collections;

/**
 * Event dispatcher.
 *
 * @package Virmire\Events
 */
class Dispatcher
{
    
    use Singleton;
    
    /**
     * @var Collections\TypeCollection
     */
    private $emitters;
    
    /**
     * @var Collections\TypeCollection
     */
    private $delayedListeners;
    
    /**
     * Event dispatcher initialize.
     *
     * @throws \TypeError
     */
    protected function init()
    {
        $this->emitters = new class(Emitter::class) extends Collections\TypeCollection
        {
        };
        
        $this->delayedListeners = new class(Collections\Collection::class) extends Collections\TypeCollection
        {
        };
    }
    
    /**
     * Emitter registration.
     *
     * @param object $object
     * @param Emitter $emitter
     *
     * @throws \TypeError
     */
    public function register($object, Emitter $emitter)
    {
        $class = get_class($object);
        
        if (!is_object($object)) {
            throw new \TypeError('Argument 1 passed to register method must be an instance of object');
        }
        
        $this->emitters->addItem($class, $emitter);
        $this->makeContext($emitter, $object);
        
        if ($this->delayedListeners->has($class)) {
            foreach ($this->delayedListeners->getItem($class) as $listenerId => $onArgs) {
                call_user_func_array([$this, 'on'], $onArgs);
            }
            $this->delayedListeners->deleteItem($class);
        }
    }
    
    /**
     * Check class for event emitter.
     *
     * @param string $className
     *
     * @return bool
     */
    public function hasEmitter(string $className) : bool
    {
        return $this->emitters->has($className);
    }
    
    /**
     * Subscribe to event.
     *
     * @param string $class
     * @param string $event
     * @param Listener $listener
     * @param object|null $bindTo
     *
     * @throws Collections\Exceptions\CollectionInvalidKeyException
     */
    public function on(string $class, string $event, Listener $listener, $bindTo = null)
    {
        if ($this->hasEmitter($class)) {
            /**
             * @var Emitter $emitter
             */
            $emitter = $this->emitters->getItem($class);
            $this->makeListener($event, $emitter, $listener);
            
            if ($bindTo !== null) {
                $this->makeContext($listener, $bindTo);
            }
        } else {
            if (!$this->delayedListeners->has($class)) {
                $this->delayedListeners->addItem($class, new Collections\Collection());
            }
            
            $this->delayedListeners->getItem($class)->addItem(
                $listener->getUniqId(),
                [
                    'class'    => $class,
                    'event'    => $event,
                    'listener' => $listener,
                    'bindTo'   => $bindTo
                ]
            );
        }
    }
    
    /**
     * @param AbstractEventSystem $object
     * @param $context
     */
    private function makeContext(AbstractEventSystem $object, $context)
    {
        $proxy = function () use ($object, $context) {
            $object->setContext($context);
        };
        $proxy->call($object);
    }
    
    /**
     * @param string $event
     * @param Emitter $emitter
     * @param Listener $listener
     */
    private function makeListener(string $event, Emitter $emitter, Listener $listener)
    {
        $proxy = function () use ($event, $emitter, $listener) {
            $emitter->addListener($event, $listener);
        };
        $proxy->call($emitter);
    }
}