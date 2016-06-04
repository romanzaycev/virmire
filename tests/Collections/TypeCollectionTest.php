<?php

use Virmire\Collections\TypeCollection;
use Virmire\Collections\Exceptions;

class TestClass{}
class TestClassWrongType{}

class TypeCollectionTest extends PHPUnit_Framework_TestCase
{

    public function testTypeCollectionConstruct()
    {
        $tc = new TypeCollection(TestClass::class);
        $this->assertEquals(TestClass::class, $tc->getType());
    }

    public function testTypeCollectionAddItem()
    {
        $tc = new TypeCollection(TestClass::class);
        $foo = new TestClass();
        $tc->addItem('foo', $foo);
        $this->assertEquals($foo, $tc->getItem('foo'));
    }

    public function testTypeCollectionAddItemWithArrayAcces()
    {
        $tc = new TypeCollection(TestClass::class);
        $foo = new TestClass();
        $tc['foo'] = $foo;
        $this->assertEquals($foo, $tc->getItem('foo'));
    }

    public function testTypeCollectionAddItemWithWrongType()
    {
        $tc = new TypeCollection(TestClass::class);
        $this->expectException(TypeError::class);
        $tc->addItem('foo', new TestClassWrongType());
    }
    
}
