<?php

use Virmire\Http\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    private $response;
    
    public function setUp()
    {
        $this->response = new Response();
    }
    
    public function testResponseMethodChaining()
    {
        $this->assertInstanceOf(Response::class, $this->response->setStatusCode(200));
        $this->assertInstanceOf(Response::class, $this->response->setHeader('Foo', 'bar'));
        $this->assertInstanceOf(Response::class, $this->response->writeHeaders());
        $this->assertInstanceOf(Response::class, $this->response->writeBody());
    }
    
    public function testResponseSetWrongStatusCode()
    {
        $this->expectException(Virmire\Http\Exceptions\HttpResponseException::class);
        $this->response->setStatusCode(1);
    }
    
    public function testResponseSetStatusCode()
    {
        $this->response->setStatusCode(300);
        $this->assertEquals(300, $this->response->getStatusCode());
    }
    
    public function testResponseRedirect()
    {
        $this->response->setStatusCode(300);
        $this->assertTrue($this->response->isRedirect());
    }
    
    public function testSetHttpHeader()
    {
        $this->response->setHeader('Foo', 'bar');
        $this->assertArrayHasKey('Foo', $this->response->getHeaders());
        $this->assertTrue($this->response->getHeaders()['Foo'] === 'bar');
    }
    
    public function testSetBody()
    {
        $body = 'foo';
        $this->response->setBody($body);
        $this->assertEquals($body, $this->response->getBody());
    }
    
    public function testWriteBody()
    {
        $body = 'foo';
        $this->response->setBody($body);
        ob_start();
        $this->response->writeBody();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($body, $output);
    }
    
    public function testWriteResponse()
    {
        $body = 'foo';
        $this->response->setBody($body);
        ob_start();
        $this->response->sendResponse();
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($body, $output);
    }
}
