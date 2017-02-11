<?php declare(strict_types = 1);

namespace Virmire\Interfaces;

/**
 * Interface ContainerInterface
 *
 * @package Virmire\Interfaces
 */
interface ContainerInterface
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name);
    
    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value);
    
    /**
     * @param string $name
     * @param callable $callback
     */
    public function lazy(string $name, callable $callback);
    
    /**
     * @param string $name
     *
     * @return bool
     */
    public function isRequested(string $name) : bool;
}
