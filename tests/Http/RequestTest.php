<?php

use Virmire\Http\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Virmire\Http\Request
     */
    private $request;
    
    /**
     * @var array
     */
    private $_GET = [
        'baz' => 'bar'
    ];
    
    /**
     * @var array
     */
    private $_POST = [
        'foo' => 'bar'
    ];
    
    /**
     * @var array
     */
    private $_FILES = [
        'foo' => [
            'name'     => 'foo.txt',
            'type'     => 'text/plain',
            'tmp_name' => 'php00000',
            'error'    => UPLOAD_ERR_OK,
            'size'     => 1
        ]
    ];
    
    /**
     * @var array
     */
    private $_COOKIE = [
        'foo' => 'bar'
    ];
    
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        
        $_POST = $this->_POST;
        $_GET = $this->_GET;
        $_FILES = $this->_FILES;
        $_COOKIE = $this->_COOKIE;
        
        $_SERVER['X_TEST'] = 'foo';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        
        $this->request = Request::getInstance();
    }
    
    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        
        $reflection = new \ReflectionClass(Request::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null);
    }
    
    public function testRequestInstance()
    {
        $this->assertInstanceOf(Request::class, $this->request);
    }
    
    public function testRequestGet()
    {
        $this->assertEquals(
            $this->_GET['baz'],
            $this->request->get('baz')
        );
    }
    
    public function testRequestGetCamelCase()
    {
        $this->assertEquals(
            $this->_GET['baz'],
            $this->request->get('Baz')
        );
    }
    
    public function testRequestAllGet()
    {
        $this->assertEquals(
            [
                'BAZ' => 'bar'
            ],
            $this->request->get()
        );
    }
    
    public function testRequestGetWithWrongKey()
    {
        $this->assertFalse($this->request->get('foo'));
    }
    
    
    public function testRequestPost()
    {
        $this->assertEquals(
            $this->_POST['foo'],
            $this->request->getPost('foo')
        );
    }
    
    public function testRequestPostCamelCase()
    {
        $this->assertEquals(
            $this->_POST['foo'],
            $this->request->getPost('Foo')
        );
    }
    
    public function testRequestAllPost()
    {
        $this->assertEquals(
            [
                'FOO' => 'bar'
            ],
            $this->request->getPost()
        );
    }
    
    public function testRequestPostWithWrongKey()
    {
        $this->assertFalse($this->request->getPost('baz'));
    }
    
    public function testRequestGetGetPost()
    {
        $this->assertEquals(
            $this->_POST['foo'],
            $this->request->getRequest('foo')
        );
    }
}
