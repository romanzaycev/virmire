<?php declare(strict_types = 1);

namespace Virmire;

use Virmire\Collections\Collection;
use Virmire\Exceptions\ContainerException;
use Virmire\Interfaces\ContainerInterface;

/**
 * Class Container
 *
 * @package Virmire
 */
class Container implements ContainerInterface
{
    /**
     * @var Collection
     */
    private $records;
    
    /**
     * @var Collection
     */
    private $delayedRecords;
    
    /**
     * @var array
     */
    private $requestedRecordsLog = [];
    
    /**
     * Container constructor.
     *
     * @param array $records
     */
    public function __construct(array $records = [])
    {
        $this->records = new Collection();
        
        $this->delayedRecords = new Collection();
        
        if (!empty($records)) {
            foreach ($records as $k => $v) {
                $this->set($k, $v);
            }
        }
    }
    
    /**
     * Add record.
     *
     * @param string $name
     * @param $value
     *
     * @throws ContainerException
     */
    public function set(string $name, $value)
    {
        if ($this->isRecordExists($name)) {
            throw new ContainerException(sprintf('Record with key "%s" already exists', $name));
        }
        
        if (is_callable($value)) {
            $this->lazy($name, $value);
        } else {
            $this->records->addItem($name, $value);
        }
    }
    
    /**
     * Get record.
     *
     * @param string $name
     *
     * @return mixed
     * @throws ContainerException
     */
    public function get(string $name)
    {
        if ($this->isRecordExists($name)) {
            if ($this->delayedRecords->has($name)) {
                $this->records->addItem(
                    $name,
                    $this->delayedRecords[$name]()
                );
                
                $this->delayedRecords->deleteItem($name);
            }
            
            $this->requestedRecordsLog[] = $name;
            
            return $this->records->getItem($name);
        }
        
        throw new ContainerException(sprintf('Record with key "%s" does not exists', $name));
    }
    
    /**
     * Set record with lazy initialization.
     *
     * @param string $name
     * @param callable $callback
     *
     * @throws ContainerException
     */
    public function lazy(string $name, callable $callback)
    {
        if (!$this->delayedRecords->has($name) && !$this->records->has($name)) {
            $this->delayedRecords->addItem($name, $callback);
        } else {
            throw new ContainerException(sprintf('Delayed record with key "%s" already exists', $name));
        }
    }
    
    /**
     * Checking the request of record.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isRequested(string $name) : bool
    {
        if (in_array($name, $this->requestedRecordsLog)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check record exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name) : bool
    {
        return $this->isRecordExists($name);
    }
    
    /**
     * @param string $name
     * @param $value
     *
     * @throws ContainerException
     */
    public function __set(string $name, $value)
    {
        $this->set($name, $value);
    }
    
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }
    
    /**
     * @param $name
     *
     * @return bool
     */
    private function isRecordExists($name) : bool
    {
        if ($this->delayedRecords->has($name) || $this->records->has($name)) {
            return true;
        }
        
        return false;
    }
}