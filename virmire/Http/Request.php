<?php declare(strict_types = 1);

namespace Virmire\Http;

use Virmire\Traits\Singleton;

/**
 * Class Request
 *
 * @package Virmire\Http
 */
class Request
{
    
    use Singleton;
    
    /**
     * @var array
     */
    private $post = array();
    
    /**
     * @var array
     */
    private $get = array();
    
    /**
     * @var array
     */
    private $cookie = array();
    
    /**
     * @var array
     */
    private $server = array();
    
    /**
     * @var array
     */
    private $files = array();
    
    /**
     * @var string
     */
    private $ipAddress = false;
    
    /**
     * @var int
     */
    const IP_UNIDENTIFIED = 0;
    
    /**
     * @var int
     */
    const IP_V4 = 1;
    
    /**
     * @var int
     */
    const IP_V6 = 2;
    
    /**
     * HTTP Request class initialize.
     */
    protected function init()
    {
        $this->import($_POST, $this->post);
        $this->import($_GET, $this->get);
        $this->import($_COOKIE, $this->cookie);
        $this->import($_SERVER, $this->server);
        $this->import($_FILES, $this->files);
    }
    
    /**
     * Get POST element.
     *
     * @param bool $key
     *
     * @return array|bool|mixed
     */
    public function getPost($key = false)
    {
        if ($key !== false) {
            return $this->fetch((string)$key, $this->post);
        }
        
        return $this->post;
    }
    
    /**
     * Get GET element.
     *
     * @param bool $key
     *
     * @return array|bool|mixed
     */
    public function get($key = false)
    {
        if ($key !== false) {
            return $this->fetch((string)$key, $this->get);
        }
        
        return $this->get;
    }
    
    /**
     * Get REQUEST element.
     *
     * @param bool $key
     *
     * @return array|bool|mixed
     */
    public function getRequest($key = false)
    {
        if ($key !== false) {
            $key = (string)$key;
            
            return $this->getPost($key) ?: $this->get($key);
        }
        
        return array_merge($this->get, $this->post);
    }
    
    /**
     * Get COOKIE element.
     *
     * @param bool $key
     *
     * @return array|bool|mixed
     */
    public function getCookie($key = false)
    {
        if ($key !== false) {
            return $this->fetch((string)$key, $this->cookie);
        }
        
        return $this->cookie;
    }
    
    /**
     * Get element from FILES array.
     *
     * @param $key
     *
     * @return array|bool|mixed
     */
    public function getFiles($key = false)
    {
        if ($key !== false) {
            return $this->fetch((string)$key, $this->files);
        }
        
        return $this->files;
    }
    
    /**
     * Get element from SERVER array.
     *
     * @param bool $key
     *
     * @return array|bool|mixed
     */
    public function getServer($key = false)
    {
        if ($key !== false) {
            return $this->fetch((string)$key, $this->server);
        }
        
        return $this->server;
    }
    
    /**
     * Get client IP address.
     *
     * @return string
     */
    public function getIp() : string
    {
        if ($this->ipAddress !== false) {
            return $this->ipAddress;
        }
        
        $this->ipAddress = $this->getServer('REMOTE_ADDR');
        
        $ip = $this->ipAddress;
        
        switch ($this->getIpVersion($ip)) {
            case self::IP_V4:
                $method = FILTER_FLAG_IPV4;
                break;
            
            case self::IP_V6:
                $method = FILTER_FLAG_IPV6;
                break;
            
            default:
                $method = 0;
                break;
        }
        
        $result = (bool)filter_var(
            $this->ipAddress,
            FILTER_VALIDATE_IP,
            $method
        );
        
        if (!$result) {
            $this->ipAddress = '0.0.0.0';
        }
        
        return $this->ipAddress;
    }
    
    /**
     * Get IP address version.
     *
     * @param string $ip
     *
     * @return int
     */
    public function getIpVersion(string $ip)
    {
        if (strpos($ip, ':') !== false) {
            return self::IP_V6;
        } elseif (strpos($ip, '.') !== false) {
            return self::IP_V4;
        }
        
        return self::IP_UNIDENTIFIED;
    }
    
    /**
     * Check AJAX request.
     *
     * @return bool
     */
    public function isAjax() : bool
    {
        if (strtoupper($this->getServer('HTTP_X_REQUESTED_WITH')) === strtoupper('XMLHttpRequest')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @param array $from
     * @param array $to
     *
     * @return void
     */
    protected function import(array &$from, array &$to)
    {
        foreach ($from as $k => $v) {
            $to[strtoupper($k)] = $v;
        }
    }
    
    /**
     * @param string $key
     * @param array $source
     *
     * @return bool|mixed
     */
    protected function fetch(string $key, array $source)
    {
        $key = strtoupper($key);
        if (isset($source[$key]) && !empty($source[$key])) {
            return $source[$key];
        }
        
        return false;
    }
    
}
