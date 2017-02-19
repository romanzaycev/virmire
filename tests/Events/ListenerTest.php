<?php

namespace VirmireTests\Events\Listener;

use Virmire\Events;
use Virmire\Events\Traits\EventEmitter;

class EventEmitterMock
{
    use EventEmitter;

    public function __construct()
    {
        $this->initEmitter();
    }

    public function generateEvent($name)
    {
        $this->emit($name);
    }
}

class ListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testListenerConstruct()
    {
        $foo = new Events\Listener(function () {
        });
        $this->assertInstanceOf(Events\Listener::class, $foo);
    }

    public function testListenerWithWrongCallback()
    {
        $this->expectException(\TypeError::class);
        $foo = new Events\Listener('');
    }

    public function testListenerUnbind()
    {
        $emitCount = 0;
        $j = 5;
        $ee = new EventEmitterMock();
        $listener = $ee->on('foo', function () use (&$emitCount) {
            $emitCount++;
        });
        for ($i = 0; $i < $j; $i++){
            $ee->generateEvent('foo');
        }
        $listener->unbind();
        for ($i = 0; $i < $j; $i++){
            $ee->generateEvent('foo');
        }
        $this->assertEquals($emitCount, $j);
    }
}
