<?php

use Virmire\Events\Emitter;
use Virmire\Events\Dispatcher;
use Virmire\Events\Listener;

class EmitterTestClassEmit
{
}

class EmitterTest extends PHPUnit_Framework_TestCase
{
    public function testEmitterEmitWithNoListeners()
    {
        $e = new Emitter();
        $data = [];
        $isPrevented = $e->emit('onEvent', $data);
        $this->assertFalse($isPrevented);
    }
    
    public function testEmitterWithPreventedEvent()
    {
        $dispatcher = Dispatcher::getInstance();
        
        $instance = new EmitterTestClassEmit();
        $emitter = $dispatcher->register($instance);
        
        $listener = new Listener(function () {
            return false;
        });
        
        $dispatcher->on($instance, 'onEvent', $listener);
        
        $this->assertTrue($emitter->emit('onEvent'));
    }
}
