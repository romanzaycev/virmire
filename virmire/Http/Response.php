<?php declare(strict_types = 1);

namespace Virmire\Http;

/**
 * Class Response
 *
 * @package Virmire\Http
 */
class Response
{
    
    /**
     * Response constructor.
     */
    public function __construct()
    {
        
    }
    
    /**
     * @var string
     */
    protected $body = '';
    
    /**
     * @var array
     */
    protected $headers = [];
    
    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }
    
    /**
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }
    
    public function writeHeaders()
    {
        
    }
    
    public function writeBody()
    {
        
    }
    
}