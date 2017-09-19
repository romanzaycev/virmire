<?php declare(strict_types = 1);

namespace Virmire\Events;

/**
 * Class EventElement
 *
 * @package Virmire\Events
 */
abstract class EventElement extends EventSystem
{
    /**
     * @var string
     */
    protected $uniqId;

    /**
     * EventSystem constructor.
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