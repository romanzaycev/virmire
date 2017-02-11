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
     * @var array
     */
    private $_SERVER = [
        'X_TEST'                => 'foo',
        'REMOTE_ADDR'           => '127.0.0.1',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'
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
        
        $_SERVER = array_merge($_SERVER, $this->_SERVER);
        
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
    
    public function testRequestGetAllGetPost()
    {
        $this->assertEquals(
            [
                'FOO' => 'bar',
                'BAZ' => 'bar'
            ],
            $this->request->getRequest()
        );
    }
    
    public function testRequestGetCookie()
    {
        $this->assertEquals($this->_COOKIE['foo'], $this->request->getCookie('foo'));
    }
    
    public function testRequestGetCookieCamelCase()
    {
        $this->assertEquals($this->_COOKIE['foo'], $this->request->getCookie('Foo'));
    }
    
    public function testRequestGetAllCookie()
    {
        $this->assertEquals(
            [
                'FOO' => 'bar'
            ],
            $this->request->getCookie()
        );
    }
    
    public function testRequestCookieWithWrongKey()
    {
        $this->assertFalse($this->request->getCookie('baz'));
    }
    
    public function testRequestFile()
    {
        $this->assertEquals(
            $this->_FILES['foo'],
            $this->request->getFiles('foo')
        );
    }
    
    public function testRequestFileCamelCase()
    {
        $this->assertEquals(
            $this->_FILES['foo'],
            $this->request->getFiles('Foo')
        );
    }
    
    public function testRequestAllFiles()
    {
        $this->assertEquals(
            [
                'FOO' => $this->_FILES['foo']
            ],
            $this->request->getFiles()
        );
    }
    
    public function testRequestGetFileWrongKey()
    {
        $this->assertFalse($this->request->getFiles('baz'));
    }
    
    public function testRequestServer()
    {
        $this->assertEquals(
            $this->_SERVER['X_TEST'],
            $this->request->getServer('x_test')
        );
    }
    
    public function testRequestServerCamelCase()
    {
        $this->assertEquals(
            $this->_SERVER['X_TEST'],
            $this->request->getServer('X_Test')
        );
    }
    
    public function testRequestServerWrongKey()
    {
        $this->assertFalse($this->request->getServer('baz'));
    }
    
    public function testRequestGetAllServer()
    {
        $this->assertEquals(
            array_change_key_case($_SERVER, CASE_UPPER),
            $this->request->getServer()
        );
    }
    
    public function testRequestGetIp()
    {
        $this->assertEquals($this->_SERVER['REMOTE_ADDR'], $this->request->getIp());
    }
    
    public function testRequestGetIpVersion()
    {
        $this->assertEquals(Request::IP_V4, $this->request->getIpVersion('127.0.0.1'));
    }
    
    public function testRequestGetIpV6Version()
    {
        $this->assertEquals(Request::IP_V6, $this->request->getIpVersion('::1'));
    }
    
    public function testRequestGetIpVersionWithWrongIp()
    {
        $this->assertEquals(Request::IP_UNIDENTIFIED, $this->request->getIpVersion('localhost'));
    }
    
    public function testRequestIsAjax()
    {
        $this->assertTrue($this->request->isAjax());
    }
}
