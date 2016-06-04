<?php declare(strict_types = 1);

namespace Virmire\Exceptions;

/**
 * Class ConfigurationException
 *
 * @package Virmire\Exceptions
 */
class ConfigurationException extends \Exception
{
    
    private $key = '';
    
    /**
     * ConfigurationException constructor.
     *
     * @param string $message
     * @param string $key
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, string $key, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        
        $this->key = $key;
    }
    
    /**
     * Get wrong key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
}
