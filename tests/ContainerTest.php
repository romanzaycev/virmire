<?php

use Virmire\Container;
use Virmire\Exceptions\ContainerException;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testConstructContainerWithWrongArgument()
    {
        $this->expectException(\TypeError::class);
        $c = new Container('');
    }
    
    public function testConstructContainerWithArray()
    {
        $c = new Container(['foo' => 'bar']);
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testContainerSet()
    {
        $c = new Container();
        $c->set('foo', 'bar');
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testSetExistsRecord()
    {
        $c = new Container();
        $c->set('foo', 'bar');
        $this->expectException(ContainerException::class);
        $c->set('foo', 'baz');
    }
    
    public function testSetDelayedRecordWithExistKey()
    {
        $c = new Container();
        $c->set('foo', 'bar');
        $this->expectException(ContainerException::class);
        $c->lazy('foo', function () {
        });
    }
    
    public function testGetRecordWithNotExistedkey()
    {
        $c = new Container();
        $this->expectException(ContainerException::class);
        $c->get('foo');
    }
    
    public function testContainerSetDelayedRecord()
    {
        $c = new Container();
        $c->lazy('foo', function () {
            return 'bar';
        });
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testContainerSetDelayedWithSetMethod()
    {
        $c = new Container();
        $c->set('foo', function () {
            return 'bar';
        });
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testContainerRequestedRecord()
    {
        $c = new Container(['foo' => 'bar']);
        $foo = $c->get('foo');
        $this->assertTrue($c->isRequested('foo'));
    }
    
    public function testContainerHasRecord()
    {
        $c = new Container();
        $this->assertFalse($c->has('foo'));
    }
    
    public function testContainerMagicSetter()
    {
        $c = new Container();
        $c->foo = 'bar';
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testContainerMagicGetter()
    {
        $c = new Container(['foo' => 'bar']);
        $this->assertEquals('bar', $c->foo);
    }
}
