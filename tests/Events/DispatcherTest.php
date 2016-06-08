<?php

use Virmire\Events\Dispatcher;
use \Virmire\Events\Emitter;
use \Virmire\Events\Listener;

class DispatcherEmitterTestClass
{
    
    /**
     * @param Dispatcher $dispatcher
     * @param Emitter $emitter
     *
     * @throws TypeError
     */
    public function registerEmitter(Dispatcher $dispatcher, Emitter $emitter)
    {
        $dispatcher->register($this, $emitter);
    }
    
    /**
     * @param Emitter $emitter
     * @param string $event
     * @param $data
     */
    public function emit(Emitter $emitter, string $event, $data)
    {
        $emitter->emit($event, $data);
    }
    
}

class DispatcherListenerTestClass
{
    
    /**
     * @param Dispatcher $dispatcher
     * @param string $event
     * @param Listener $listener
     * @param null $bindTo
     */
    public function subscribe(Dispatcher $dispatcher, string $event, Listener $listener, $bindTo = null)
    {
        $dispatcher->on(DispatcherEmitterTestClass::class, $event, $listener, $bindTo);
    }
    
}

class DispatcherContextTestClass
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
    
    public function testDispatcherRegisterClass()
    {
        $foo = new DispatcherEmitterTestClass();
        
        $foo->registerEmitter($this->dispatcher, new Emitter());
        
        $this->assertTrue($this->dispatcher->hasEmitter(DispatcherEmitterTestClass::class));
    }
    
    public function testDispatcherRegisterWithWrongArgument()
    {
        $this->expectException(\TypeError::class);
        $this->dispatcher->register('', new Emitter());
    }
    
    public function testDispatcherHasWithNoRegisteredClass()
    {
        $this->assertFalse($this->dispatcher->hasEmitter(DispatcherEmitterTestClass::class));
    }
    
    public function testDispatcherWithDelayedRegister()
    {
        $foo = new DispatcherListenerTestClass();
        $foo->subscribe($this->dispatcher, 'onEvent', new Listener(function () {
        }));
        
        $this->assertFalse($this->dispatcher->hasEmitter(DispatcherEmitterTestClass::class));
    }
    
    public function testDispatcherWithEmitterEmit()
    {
        $event = 'onEvent';
        $data = 'Event data string';
        
        $emitter = new Emitter();
        
        $foo = new DispatcherEmitterTestClass();
        $foo->registerEmitter($this->dispatcher, $emitter);
        
        $bar = new DispatcherListenerTestClass();
        $bar->subscribe(
            $this->dispatcher,
            $event,
            new Listener(
                function ($evData) use ($data) {
                    $this->assertEquals($data, $evData);
                }
            )
        );
        
        $foo->emit($emitter, $event, $data);
    }
    
    public function testDispatcherWithDelayedRegisteredEmitterEmit()
    {
        $event = 'onEvent';
        $data = 'Event data string';
        
        $bar = new DispatcherListenerTestClass();
        $bar->subscribe(
            $this->dispatcher,
            $event,
            new Listener(
                function ($evData) use ($data) {
                    $this->assertEquals($data, $evData);
                }
            )
        );
        
        $emitter = new Emitter();
        
        $foo = new DispatcherEmitterTestClass();
        $foo->registerEmitter($this->dispatcher, $emitter);
        $foo->emit($emitter, $event, $data);
    }
    
    public function testDispatcherEmitWithChangedContext()
    {
        $event = 'onEvent';
        
        $emitter = new Emitter();
        
        $foo = new DispatcherEmitterTestClass();
        $foo->registerEmitter($this->dispatcher, $emitter);
        
        $context = new DispatcherContextTestClass();
        
        $self = $this;
        
        $bar = new DispatcherListenerTestClass();
        $bar->subscribe(
            $this->dispatcher,
            $event,
            new Listener(
                function () use ($self) {
                    $self->assertInstanceOf(DispatcherContextTestClass::class, $this);
                }
            ),
            $context
        );
        
        $foo->emit($emitter, $event, null);
    }
    
}
