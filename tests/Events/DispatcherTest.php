<?php

use Virmire\Events\Dispatcher;
use Virmire\Events\Listener;

class DispatcherEmitterMock
{
}

class DispatcherContextMock
{
}

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->dispatcher = Dispatcher::getInstance();
    }
    
    public function tearDown()
    {
        parent::tearDown();
        
        $reflection = new \ReflectionClass(Dispatcher::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null);
    }
    
    public function testDispatcherInstance()
    {
        $this->assertInstanceOf(Dispatcher::class, $this->dispatcher);
    }
    
    public function testDispatcherRegisterInstance()
    {
        $instance = new DispatcherEmitterMock();
        $this->dispatcher->register($instance);
        $this->assertTrue($this->dispatcher->hasEmitter($instance));
    }
    
    public function testDispatcherRegisterWithWrongArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->dispatcher->register('');
    }
    
    public function testDispatcherHasWithNoRegisteredInstance()
    {
        $this->assertFalse($this->dispatcher->hasEmitter(new DispatcherEmitterMock()));
    }
    
    public function testDispatcherWithDelayedRegister()
    {
        $eventName = 'onEvent';
        $instance = new DispatcherEmitterMock();
        $this->dispatcher->on(
            $instance,
            $eventName,
            new Listener(
                function ($data) {
                }
            )
        );
        $this->assertFalse($this->dispatcher->hasEmitter($instance));
    }
    
    public function testDispatcherWithEmitterEmit()
    {
        $eventName = 'onEvent';
        $eventData = 'foo';
        $instance = new DispatcherEmitterMock();
        $emitter = $this->dispatcher->register($instance);
        $this->dispatcher->on(
            $instance,
            $eventName,
            new Listener(
                function ($data) use ($eventData) {
                    $this->assertEquals($eventData, $data);
                }
            )
        );
        $emitter->emit($eventName, $eventData);
    }
    
    public function testDispatcherWithDelayedRegisteredEmitterEmit()
    {
        $eventName = 'onEvent';
        $eventData = 'foo';
        $instance = new DispatcherEmitterMock();
        $this->dispatcher->on(
            $instance,
            $eventName,
            new Listener(
                function ($data) use ($eventData) {
                    $this->assertEquals($eventData, $data);
                }
            )
        );
        $emitter = $this->dispatcher->register($instance);
        $emitter->emit($eventName, $eventData);
    }
    
    public function testDispatcherEmitWithChangedContext()
    {
        $eventName = 'onEvent';
        $instance = new DispatcherEmitterMock();
        $context = new DispatcherContextMock();
        $emitter = $this->dispatcher->register($instance);
        $self = $this;
        $listener = new Listener(function () use ($self) {
            $self->assertInstanceOf(DispatcherContextMock::class, $this);
        });
        $this->dispatcher->on($instance, $eventName, $listener, $context);
        $emitter->emit($eventName);
    }
}
