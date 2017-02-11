<?php

use Virmire\Collections\TypeCollection;
use Virmire\Collections\Exceptions;

class TypeCollectionTestClass
{
}

class TypeCollectionTestClassWrongType
{
}

class TypeCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testTypeCollectionConstruct()
    {
        $tc = new TypeCollection(TypeCollectionTestClass::class);
        $this->assertEquals(TypeCollectionTestClass::class, $tc->getType());
    }
    
    public function testTypeCollectionAddItem()
    {
        $tc = new TypeCollection(TypeCollectionTestClass::class);
        $foo = new TypeCollectionTestClass();
        $tc->addItem('foo', $foo);
        $this->assertEquals($foo, $tc->getItem('foo'));
    }
    
    public function testTypeCollectionAddItemWithArrayAccess()
    {
        $tc = new TypeCollection(TypeCollectionTestClass::class);
        $foo = new TypeCollectionTestClass();
        $tc['foo'] = $foo;
        $this->assertEquals($foo, $tc->getItem('foo'));
    }
    
    public function testTypeCollectionAddItemWithWrongType()
    {
        $tc = new TypeCollection(TypeCollectionTestClass::class);
        $this->expectException(TypeError::class);
        $tc->addItem('foo', new TypeCollectionTestClassWrongType());
    }
    
    public function testTypeCollectionAddItemsWithConstructor()
    {
        $tc = new TypeCollection(
            TypeCollectionTestClass::class,
            [
                'foo' => new TypeCollectionTestClass(),
                'bar' => new TypeCollectionTestClass()
            ]
        );
        $this->assertTrue(($tc->has('foo') && $tc->has('bar')));
    }
}
