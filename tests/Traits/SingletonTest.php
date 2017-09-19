<?php

namespace VirmireTests\Traits;

use Virmire\Traits\Singleton;

class SingletonMockClass
{
    use Singleton;

    public $foo;

    protected  function init(): void
    {
        $this->foo = 'bar';
    }
}

class SingletonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SingletonMockClass
     */
    private $instance;

    protected function setUp()
    {
        parent::setUp();

        $this->instance = SingletonMockClass::getInstance();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $reflection = new \ReflectionClass(SingletonMockClass::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null);
    }

    public function testPrivateSingletonInitializer()
    {
        $this->assertEquals('bar', $this->instance->foo);
    }
}
