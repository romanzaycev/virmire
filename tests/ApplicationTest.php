<?php

use Virmire\Application;
use Virmire\Container;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testApplicationWithWrongContainerArgument()
    {
        $this->expectException('TypeError');
        $application = new Application('');
    }
    
    public function testApplicationConstruct()
    {
        $c = new Container([
            'eventDispatcher' => Virmire\Events\Dispatcher::getInstance()
        ]);
        $a = new Application($c);
        
        $this->assertEquals(Virmire\Events\Dispatcher::getInstance(), $a->eventDispatcher);
    }
}
