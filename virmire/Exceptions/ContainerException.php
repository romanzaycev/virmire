<?php declare(strict_types = 1);

namespace Virmire\Exceptions;

/**
 * Class ContainerException
 *
 * @package Virmire\Exceptions
 */
class ContainerException extends \Exception
{
    
    /**
     * ContainerException constructor.
     *
     * @param string $message
     * @param string $code
     * @param \Exception|null $previous
     */
    public function __construct(string $message = '', $code = '', \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
}