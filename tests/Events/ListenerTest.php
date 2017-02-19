<?php

namespace VirmireTests\Events;

use Virmire\Events\Listener;

class ListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testListenerConstruct()
    {
        $foo = new Listener(function () {
        });
        $this->assertInstanceOf(Listener::class, $foo);
    }
    
    public function testListenerWithWrongCallback()
    {
        $this->expectException(\TypeError::class);
        $foo = new Listener('');
    }
}
