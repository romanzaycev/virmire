<?php

namespace VirmireTests\Application;

use Virmire\Application;
use Virmire\Container;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testApplicationWithWrongContainerArgument()
    {
        $this->expectException('TypeError');
        /** @noinspection PhpUnusedLocalVariableInspection */
        $application = new Application('');
    }

    public function testApplicationConstruct()
    {
        $c = new Container([
            'foo' => 'bar'
        ]);
        $a = new Application($c);

        $this->assertEquals('bar', $a->foo);
    }
}
