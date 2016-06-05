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
        $listener = new Listener(function ($evData, &$isPrevented) {
            $isPrevented = true;
        });
        Dispatcher::getInstance()->on(EmitterTestClassEmit::class, 'onEvent', $listener);
        
        $foo = new EmitterTestClassEmit();
        $emitter = new Emitter();
        
        $proxy = function () use ($foo, $emitter) {
            Dispatcher::getInstance()->register($this, $emitter);
        };
        $proxy->call($foo);
        
        $data = [];
        $this->assertTrue($emitter->emit('onEvent', $data));
    }
    
}
