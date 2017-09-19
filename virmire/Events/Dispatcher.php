<?php declare(strict_types = 1);

namespace Virmire\Events;

use Virmire\Collections;
use Virmire\Collections\Collection;
use Virmire\Collections\TypedCollection;
use Virmire\Traits\Singleton;

/**
 * Event dispatcher.
 *
 * @package Virmire\Events
 */
class Dispatcher extends EventSystem
{
    use Singleton;

    /**
     * @var TypedCollection<Emitter>
     */
    private $emitters;

    /**
     * @var Collection
     */
    private $instances;

    /**
     * @var TypedCollection<Listener>
     */
    private $delayedListeners;

    /**
     * Event dispatcher initialize.
     *
     * @throws \TypeError
     */
    protected function init()
    {
        $this->emitters = new class(Emitter::class) extends TypedCollection
        {
            /**
             * @param $key
             *
             * @return Emitter
             */
            public function getItem($key) : Emitter
            {
                return parent::getItem($key);
            }
        };

        $this->delayedListeners = new class(Collection::class) extends TypedCollection
        {
            /**
             * @param $key
             *
             * @return Collection
             */
            public function getItem($key) : Collection
            {
                return parent::getItem($key);
            }
        };

        $this->instances = new Collection();
    }

    /**
     * Event emitter registration.
     *
     * @param object $instance
     *
     * @return Emitter
     * @throws Collections\Exceptions\CollectionInvalidKeyException
     */
    public function register($instance)
    {
        if (!\is_object($instance)) {
            throw new \InvalidArgumentException('First argument must to be instance of class');
        }

        $emitter = new Emitter();
        $instanceId = $this->ensureEventEmitterId($instance, $emitter);
        $delayedId = $this->getDelayedId($instance);

        $this->emitters->addItem($instanceId, $emitter);
        $this->makeContext($emitter, $instance);

        if ($this->delayedListeners->has($delayedId)) {
            /**
             * @var $delayedListenersCollection TypedCollection<Listener>
             */
            $delayedListenersCollection = $this->delayedListeners->getItem($delayedId);
            foreach ($delayedListenersCollection as $listenerId => $onArgs) {
                call_user_func_array([$this, 'on'], $onArgs);
                $delayedListenersCollection->getItem($listenerId);
            }
            if ($delayedListenersCollection->count() === 0) {
                $this->delayedListeners->deleteItem($delayedId);
            }
        }

        return $emitter;
    }

    /**
     * Check class for event emitter.
     *
     * @param object $instance
     *
     * @return bool
     */
    public function hasEmitter($instance) : bool
    {
        if (!\is_object($instance)) {
            throw new \InvalidArgumentException('First argument must to be instance of class');
        }

        $instanceId = $this->ensureEventEmitterId($instance);

        if ($instanceId === false) {
            return false;
        }

        return $this->instances->has($instanceId);
    }

    /**
     * Subscribe to event.
     *
     * @param object $instance
     * @param string $eventName
     * @param Listener $listener
     * @param object|null $bindTo
     *
     * @throws Collections\Exceptions\CollectionInvalidKeyException
     */
    public function on($instance, string $eventName, Listener $listener, $bindTo = null)
    {
        if (!\is_object($instance)) {
            throw new \InvalidArgumentException('First argument must to be instance of class');
        }

        if ($this->hasEmitter($instance)) {
            $instanceId = $this->ensureEventEmitterId($instance);

            /**
             * @var Emitter $emitter
             */
            $emitter = $this->emitters->getItem($instanceId);
            $this->makeLink($eventName, $emitter, $listener);

            if ($bindTo !== null && is_object($bindTo)) {
                $this->makeContext($listener, $bindTo);
            }
        } else {
            $delayedId = $this->getDelayedId($instance);

            if (!$this->delayedListeners->has($delayedId)) {
                $this->delayedListeners->addItem($delayedId, new Collection());
            }

            $this->delayedListeners->getItem($delayedId)->addItem(
                $listener->getUniqId(),
                [
                    'instance'  => $instance,
                    'eventName' => $eventName,
                    'listener'  => $listener,
                    'bindTo'    => $bindTo
                ]
            );
        }
    }

    /**
     * @param EventElement $eventElement
     * @param object $context
     */
    private function makeContext(EventElement $eventElement, $context)
    {
        $proxy = function () use ($eventElement, $context) {
            $eventElement->setContext($context);
        };
        $proxy->call($eventElement);
    }

    /**
     * @param string $eventName
     * @param Emitter $emitter
     * @param Listener $listener
     */
    private function makeLink(string $eventName, Emitter $emitter, Listener $listener)
    {
        // @TODO: Rethink this place, protected-hack should to be removed
        $emitterProxy = function () use ($eventName, $emitter, $listener) {
            /* @noinspection Annotator */
            $emitter->addListener($eventName, $listener);
        };
        $listenerProxy = function () use ($eventName, $emitter, $listener) {
            /* @noinspection Annotator */
            $listener->setEmitter($emitter);
            /* @noinspection Annotator */
            $listener->setEventName($eventName);
        };
        $emitterProxy->call($emitter);
        $listenerProxy->call($listener);
    }

    /**
     * @param object $instance
     * @param Emitter|null $emitter
     *
     * @return string
     * @throws Collections\Exceptions\CollectionKeyHasUseException
     */
    private function ensureEventEmitterId($instance, $emitter = null) : string
    {
        if (!\is_object($instance)) {
            throw new \InvalidArgumentException('First argument must to be instance of class');
        }

        if ($emitter !== null) {
            $emitterId = $emitter->getUniqId();
            if (!$this->instances->has($emitterId)) {
                $this->instances->addItem($emitterId, $instance);
            }

            return $emitterId;
        }

        $emitterId = array_search($instance, $this->instances->toArray(), true);

        if ($emitterId === false) {
            return $this->getDelayedId($instance);
        }

        return $emitterId;
    }

    /**
     * @param object $instance
     *
     * @return string
     */
    private function getDelayedId($instance)
    {
        return 'delayed-' . get_class($instance);
    }
}