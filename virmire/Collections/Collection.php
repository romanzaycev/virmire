<?php declare(strict_types = 1);

namespace Virmire\Collections;

use Virmire\Collections\Exceptions;

/**
 * Class Collection
 *
 * @package Virmire\Collections
 */
class Collection implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array
     */
    private $container = [];
    
    /**
     * @var int
     */
    private $position = 0;
    
    /**
     * Collection constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->position = 0;
        
        $this->container = $data;
    }
    
    /**
     * @param $key
     * @param $object
     *
     * @throws Exceptions\CollectionKeyHasUseException
     */
    public function addItem($key, $object)
    {
        if (array_key_exists($key, $this->container)) {
            throw new Exceptions\CollectionKeyHasUseException(sprintf('Invalid key "%s"', $key));
        }
        
        $this->container[$key] = $object;
    }
    
    /**
     * @param $key
     *
     * @return mixed
     * @throws Exceptions\CollectionInvalidKeyException
     */
    public function getItem($key)
    {
        if (!array_key_exists($key, $this->container)) {
            throw new Exceptions\CollectionInvalidKeyException(sprintf('Invalid key "%s"', $key));
        }
        
        return $this->container[$key];
    }
    
    /**
     * @param $key
     *
     * @throws Exceptions\CollectionInvalidKeyException
     */
    public function deleteItem($key)
    {
        if (!array_key_exists($key, $this->container)) {
            throw new Exceptions\CollectionInvalidKeyException(sprintf('Invalid key "%s"', $key));
        }
        
        unset($this->container[$key]);
    }
    
    /**
     * @param $object
     */
    public function retain($object)
    {
        $keys = array_keys($this->container, $object, true);
        if ($keys !== false && is_array($keys)) {
            foreach ($keys as $key) {
                $this->deleteItem($key);
            }
        }
    }
    
    /**
     * Get collection keys.
     *
     * @return array
     */
    public function getKeys() : array
    {
        return array_keys($this->container);
    }
    
    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key) : bool
    {
        return array_key_exists($key, $this->container);
    }
    
    /**
     * @param $object
     *
     * @return bool
     */
    public function contains($object) : bool
    {
        return in_array($object, $this->container, true);
    }
    
    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->container;
    }
    
    /**
     * Interfaces implementation.
     */
    
    public function offsetSet($offset, $value)
    {
        $this->addItem($offset, $value);
    }
    
    public function offsetGet($offset)
    {
        return $this->getItem($offset);
    }
    
    public function offsetExists($offset) : bool
    {
        return $this->has($offset);
    }
    
    public function offsetUnset($offset)
    {
        $this->deleteItem($offset);
    }
    
    public function rewind()
    {
        reset($this->container);
    }
    
    public function current()
    {
        return current($this->container);
    }
    
    public function key()
    {
        return key($this->container);
    }
    
    public function next()
    {
        return next($this->container);
    }
    
    public function valid()
    {
        $key = key($this->container);
        
        return isset($this->container[$key]);
    }
    
    public function count()
    {
        return count($this->container);
    }
}
