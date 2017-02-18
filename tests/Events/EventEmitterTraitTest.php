<?php

use Virmire\Events\EventEmitterTrait;
use Virmire\Events\Dispatcher;

class EventEmitterMock
{
    use EventEmitterTrait;
    
    public function __construct()
    {
        $this->initEmitter();
    }
    
    public function generateEvent($name, $data)
    {
        $this->emit($name, $data);
    }
}

class EventEmitterTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EventEmitterMock
     */
    private $ee;
    
    public function setUp()
    {
        $this->ee = new EventEmitterMock();
    }
    
    public function testEventEmitterHasEmitter()
    {
        $this->assertTrue(Dispatcher::getInstance()->hasEmitter($this->ee));
    }
    
    public function testEventEmitterShortOnSyntax()
    {
        $this->ee->on('foo', function ($data) {
            $this->assertEquals('bar', $data);
        });
        $this->ee->generateEvent('foo', 'bar');
    }
}
