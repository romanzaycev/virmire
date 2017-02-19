<?php

namespace VirmireTests\Configuration;

use Virmire\Configuration;
use Virmire\Exceptions\ConfigurationException;

/**
 * Class ConfigurationTest.
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithWrongParam()
    {
        $this->expectException(\TypeError::class);
        $c = new Configuration(null);
    }
    
    public function testGetter()
    {
        $c = new Configuration(['foo' => 'bar']);
        $this->assertEquals('bar', $c->get('foo'));
    }
    
    public function testMagicGetter()
    {
        $c = new Configuration(['foo' => 'bar']);
        $this->assertEquals('bar', $c->foo);
    }
    
    public function testGetWithDefaultValue()
    {
        $c = new Configuration();
        $this->assertEquals('bar', $c->get('foo', 'bar'));
    }
    
    public function testGetNotExistedParam()
    {
        $c = new Configuration();
        $this->expectException(ConfigurationException::class);
        $c->get('foo');
    }
    
    public function testGetNestedValue()
    {
        $c = new Configuration([
            'foo' => [
                'bar' => 'baz'
            ]
        ]);
        $this->assertEquals(
            'baz',
            $c->get('foo.bar')
        );
    }
}
