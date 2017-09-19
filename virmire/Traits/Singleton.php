<?php declare(strict_types = 1);

namespace Virmire\Traits;

/**
 * Trait Singleton
 *
 * @package Virmire\Traits
 */
trait Singleton
{
    protected static $instance = null;

    /**
     * @return static
     */
    final public static function getInstance()
    {
        return (static::$instance !== null)
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
     *
     * This method should to be implemented
     */
    protected abstract function init() : void;

    /**
     * Protected magic wakeup method.
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
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
