<?php declare(strict_types = 1);

namespace Virmire;

use Virmire\Exceptions\ConfigurationException;

/**
 * Class Configuration
 *
 * @package Virmire
 */
class Configuration
{
    const SEPARATOR = '.';
    
    /**
     * @var array
     */
    protected $configuration = [];
    
    /**
     * Configuration constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
    
    /**
     * Get configuration item by name or name path
     *
     * @param string $name
     * @param mixed|null $default
     *
     * @return array|mixed|null
     * @throws ConfigurationException
     */
    public function get(string $name, $default = null)
    {
        if (strpos($name, self::SEPARATOR) !== false) {
            $keys = explode(self::SEPARATOR, $name);
            
            $previous = $this->configuration;
            $previousKeyPath = '';
            
            foreach ($keys as $key) {
                $previousKeyPath .= self::SEPARATOR . $key;
                if (array_key_exists($key, $previous)) {
                    $previous = $previous[$key];
                } else {
                    $name = trim($previousKeyPath, self::SEPARATOR);
                    goto throws;
                }
            }
            
            return $previous;
        } else {
            if (array_key_exists($name, $this->configuration)) {
                return $this->configuration[$name];
            }
        }
        
        throws:{
        if ($default === null) {
            throw new ConfigurationException(sprintf('Configuration key "%s" is not defined', $name), $name);
        } else {
            return $default;
        }
    }
    }
    
    /**
     * @param string $name
     *
     * @return array|mixed
     * @throws ConfigurationException
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }
}
