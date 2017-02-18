<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Class AbstractEventSystem
 *
 * @package Virmire\Events
 */
abstract class AbstractEventSystem
{
    /**
     * @var object
     */
    private $context = null;
    
    /**
     * @param object $object
     */
    final protected function setContext($object)
    {
        $this->context = $object;
    }
    
    /**
     * @return object
     */
    final protected function getContext()
    {
        return $this->context;
    }
    
    /**
     * @return bool
     */
    final protected function hasContext() : bool
    {
        return !is_null($this->context);
    }
}