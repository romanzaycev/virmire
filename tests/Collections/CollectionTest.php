<?php

use Virmire\Collections\Collection;
use Virmire\Collections\Exceptions;

/**
 * Class CollectionTest
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{

    public function testCollectionWithArrayConstruct()
    {
        $c = new Collection(['foo' => 'bar']);
        $this->assertEquals('bar', $c->getItem('foo'));
    }

    public function testCollectionAddItem()
    {
        $c = new Collection();
        $c->addItem('foo', 'bar');
        $this->assertEquals('bar', $c->getItem('foo'));
    }

    public function testCollectionDeleteItem()
    {
        $c = new Collection(['foo' => 'bar']);
        $c->deleteItem('foo');
        $this->assertFalse($c->has('foo'));
    }

    public function testCollectionAddExistingItem()
    {
        $c = new Collection(['foo' => 'bar']);
        $this->expectException(Exceptions\CollectionKeyHasUseException::class);
        $c->addItem('foo', 'baz');
    }

    public function testCollectionDeleteNotExistsItem()
    {
        $c = new Collection();
        $this->expectException(Exceptions\CollectionInvalidKeyException::class);
        $c->deleteItem('foo');
    }

    public function testCollectionKeys(){
        $с = new Collection(['foo' => 'bar', 'baz' => 'foo']);
        $this->assertEquals(['foo', 'baz'], $с->getKeys());
    }

    public function testCollectionToArray()
    {
        $c = new Collection();
        $c->addItem('bar', 'foo');
        $c->addItem('baz', 'foo');
        $this->assertEquals(['bar' => 'foo', 'baz' => 'foo'], $c->toArray());
    }

    public function testCollectionArrayAccessImplements(){
        $c = new Collection();
        $c['foo'] = 'bar';
        $this->assertEquals('bar', $c->getItem('foo'));
    }

    public function testCollectionIteratorImplements()
    {
        $c = new Collection();
        $this->assertTrue($c instanceof \Iterator);
    }

    public function testCollectionCountableImplements()
    {
        $c = new Collection();
        $this->assertTrue($c instanceof \Countable);
    }

}
