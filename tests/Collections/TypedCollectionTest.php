<?php

namespace VirmireTests\Collections;

use Virmire\Collections\TypedCollection;

class TypeCollectionTestClass
{
}

class TypedCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testTypedCollectionConstruct()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $this->assertEquals(TypeCollectionTestClass::class, $tc->getType());
    }

    public function testTypedCollectionAddItem()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $foo = new TypeCollectionTestClass();
        $tc->addItem('foo', $foo);
        $this->assertEquals($foo, $tc->getItem('foo'));
    }

    public function testTypedCollectionAddItemWithArrayAccess()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $foo = new TypeCollectionTestClass();
        $tc['foo'] = $foo;
        $this->assertEquals($foo, $tc->getItem('foo'));
    }

    public function testTypedCollectionAddItemWithWrongType()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $this->expectException(\TypeError::class);
        $tc->addItem('foo', new \stdClass());
    }

    public function testTypedCollectionAddItemsWithConstructor()
    {
        $tc = new TypedCollection(
            TypeCollectionTestClass::class,
            [
                'foo' => new TypeCollectionTestClass(),
                'bar' => new TypeCollectionTestClass()
            ]
        );
        $this->assertTrue(($tc->has('foo') && $tc->has('bar')));
    }

    public function testTypedCollectionRetainWithWrongType()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $this->expectException(\TypeError::class);
        $tc->retain(new \stdClass());
    }

    public function testTypedCollectionContainsWithWrongType()
    {
        $tc = new TypedCollection(TypeCollectionTestClass::class);
        $this->expectException(\TypeError::class);
        $tc->contains(new \stdClass());
    }
}
