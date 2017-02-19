<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Class AbstractEventElement
 *
 * @package Virmire\Events
 */
abstract class AbstractEventElement extends AbstractEventSystem
{
    /**
     * @var string
     */
    protected $uniqId;
    
    /**
     * AbstractEventSystem constructor.
     */
    public function __construct()
    {
        $this->uniqId = uniqid();
    }
    
    /**
     * @return string
     */
    final public function getUniqId() : string
    {
        return $this->uniqId;
    }
}