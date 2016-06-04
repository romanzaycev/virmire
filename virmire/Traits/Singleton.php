<?php declare(strict_types = 1);

namespace Virmire\Traits;

/**
 * Trait Singleton
 *
 * @package Virmire\Traits
 */
trait Singleton
{
    
    protected static $instance;
    
    /**
     * @return static
     */
    final public static function getInstance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }
    
    /**
     * Singleton constructor.
     */
    final private function __construct()
    {
        $this->init();
    }
    
    /**
     * Singleton initialization method.
     */
    protected function init()
    {
    }
    
    /**
     * Protected magic wakeup method.
     */
    final private function __wakeup()
    {
    }
    
    /**
     * Protected magic clone method.
     */
    final private function __clone()
    {
    }
    
}
